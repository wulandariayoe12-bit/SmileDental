<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class OnoPayService
{
    public function checkUser(string $phoneNumber): array
    {
        return $this->post('/merchant/check-user', [
            'phone_number' => $this->normalizePhone($phoneNumber),
        ]);
    }

    public function checkBalance(string $phoneNumber): array
    {
        return $this->post('/merchant/check-balance', [
            'phone_number' => $this->normalizePhone($phoneNumber),
        ]);
    }

    public function payByQr(string $receiverPhone, string $payerPhone, int $amount, string $description): array
    {
        $qr = $this->post('/payment/qr/generate', [
            'phone_number' => $this->normalizePhone($receiverPhone),
            'amount' => $amount,
            'merchant_code' => (string) config('services.onopay.merchant_code', 'SMILEDENTAL'),
            'description' => $description,
            'qr_mode' => 'single_use',
        ]);

        $qrCode = (string) data_get($qr, 'data.qr_code', '');

        if ($qrCode === '') {
            throw new RuntimeException('OnoPay tidak mengembalikan kode QR pembayaran.');
        }

        return $this->post('/payment/qr/pay', [
            'qr_code' => $qrCode,
            'payer_phone' => $this->normalizePhone($payerPhone),
        ]);
    }

    public function createQrisInstruction(object $appointment, object $payment, string $patientName): array
    {
        $reference = $this->referenceFor((int) $appointment->id, (int) $payment->id);
        $expiresAt = now()->addMinutes((int) config('services.onopay.qris_expiry_minutes', 30));
        $payload = $this->fallbackPayload($reference, (int) $payment->total_harga, $patientName);

        if ($this->hasRemoteConfig()) {
            $remoteInstruction = $this->createRemoteQris(
                $reference,
                (int) $payment->total_harga,
                $patientName,
                $expiresAt,
                'Booking SmileDental'
            );

            $payload = $remoteInstruction['qris_payload'] ?: $payload;
            $expiresAt = $remoteInstruction['expires_at'] ?? $expiresAt;
            $imageUrl = $remoteInstruction['qris_image_url'] ?? null;
        } else {
            $imageUrl = null;
        }

        return [
            'provider' => 'OnoPay',
            'state' => 'waiting_payment',
            'reference' => $reference,
            'amount' => (int) $payment->total_harga,
            'currency' => 'IDR',
            'qris_payload' => $payload,
            'qris_image_url' => $imageUrl,
            'expires_at' => $expiresAt->toIso8601String(),
            'instructions' => [
                'Scan QRIS menggunakan aplikasi bank atau e-wallet yang mendukung QRIS.',
                'Pastikan nominal dan referensi pembayaran sesuai.',
                'Status akan menjadi lunas setelah pembayaran terverifikasi oleh OnoPay/admin.',
            ],
        ];
    }

    public function webUrl(): string
    {
        return rtrim((string) config('services.onopay.web_url', 'http://onopay.web.id'), '/');
    }

    public function apiUrl(): string
    {
        return rtrim((string) config('services.onopay.api_url', 'http://onopay.web.id/api/v1'), '/');
    }

    private function hasRemoteConfig(): bool
    {
        return filled(config('services.onopay.merchant_phone'));
    }

    private function createRemoteQris(
        string $reference,
        int $amount,
        string $patientName,
        CarbonInterface $expiresAt,
        string $description
    ): array {
        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->post($this->apiUrl() . '/payment/qr/generate', [
                    'reference' => $reference,
                    'amount' => $amount,
                    'phone_number' => (string) config('services.onopay.merchant_phone', ''),
                    'merchant_code' => (string) config('services.onopay.merchant_code', 'SMILEDENTAL'),
                    'description' => $description,
                    'qr_mode' => 'single_use',
                ]);
        } catch (Throwable) {
            return [
                'qris_payload' => '',
                'qris_image_url' => null,
                'expires_at' => $expiresAt,
            ];
        }

        if (! $response->successful()) {
            return [
                'qris_payload' => '',
                'qris_image_url' => null,
                'expires_at' => $expiresAt,
            ];
        }

        $data = $response->json();

        $remoteExpiresAt = data_get($data, 'expires_at', data_get($data, 'data.expires_at'));

        return [
            'qris_payload' => (string) data_get($data, 'data.qr_code', data_get($data, 'qris_payload', data_get($data, 'data.qris_payload', ''))),
            'qris_image_url' => data_get($data, 'data.qr_image', data_get($data, 'qris_image_url', data_get($data, 'data.qris_image_url'))),
            'expires_at' => $remoteExpiresAt ? Carbon::parse($remoteExpiresAt) : $expiresAt,
        ];
    }

    private function post(string $path, array $payload): array
    {
        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->asJson()
                ->post($this->apiUrl() . $path, $payload);
        } catch (Throwable $error) {
            throw new RuntimeException('Tidak bisa menghubungi OnoPay di ' . $this->webUrl() . ': ' . $error->getMessage());
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('Response OnoPay tidak valid.');
        }

        if (! $response->successful() || data_get($data, 'success') === false) {
            throw new RuntimeException((string) data_get($data, 'message', 'Request ke OnoPay gagal diproses.'));
        }

        return $data;
    }

    private function normalizePhone(string $phoneNumber): string
    {
        $digits = preg_replace('/\D+/', '', $phoneNumber) ?? '';

        if (str_starts_with($digits, '62')) {
            return '0' . substr($digits, 2);
        }

        return $digits;
    }

    private function referenceFor(int $appointmentId, int $paymentId): string
    {
        return 'ONOPAY-SD-' . $appointmentId . '-' . $paymentId;
    }

    private function fallbackPayload(string $reference, int $amount, string $patientName): string
    {
        return 'ONOPAY|QRIS|'
            . 'MERCHANT=' . Str::slug((string) config('app.name', 'SmileDental')) . '|'
            . 'REF=' . $reference . '|'
            . 'AMOUNT=' . $amount . '|'
            . 'CURRENCY=IDR|'
            . 'CUSTOMER=' . Str::limit(Str::ascii($patientName), 40, '');
    }
}

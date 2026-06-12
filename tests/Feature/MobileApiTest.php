<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite extension is not available in this PHP environment.');
        }

        parent::setUp();
    }

    public function test_mobile_register_creates_patient_and_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Pasien Mobile',
            'email' => 'pasien@example.test',
            'password' => 'password123',
            'phone' => '081234567890',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('status', true)
            ->assertJsonStructure(['token', 'user' => ['patient_id'], 'patient' => ['id', 'nama', 'no_hp']]);

        $this->assertDatabaseHas('pasien', [
            'nama' => 'Pasien Mobile',
            'no_hp' => '081234567890',
        ]);
    }

    public function test_mobile_booking_and_payment_flow(): void
    {
        $register = $this->postJson('/api/register', [
            'name' => 'Pasien Booking',
            'email' => 'booking@example.test',
            'password' => 'password123',
            'phone' => '081234567891',
        ]);

        $token = $register->json('token');

        $doctorId = DB::table('dokter')->insertGetId([
            'nama' => 'drg. Integrasi',
            'spesialisasi' => 'Umum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('layanan_klinik')->insert([
            'nama_layanan' => 'Konsultasi',
            'harga' => 250000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $appointment = $this
            ->withToken($token)
            ->postJson('/api/appointments', [
                'doctor_id' => $doctorId,
                'tanggal' => '2026-06-15',
                'jam' => '09:30',
                'keluhan' => 'Gigi ngilu',
            ]);

        $appointment
            ->assertCreated()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.keluhan', 'Gigi ngilu')
            ->assertJsonPath('data.is_paid', false)
            ->assertJsonPath('data.payment.provider', 'OnoPay')
            ->assertJsonPath('data.payment.state', 'waiting_payment');

        $appointmentId = $appointment->json('data.id');

        $this
            ->withToken($token)
            ->postJson("/api/appointments/{$appointmentId}/pay")
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.is_paid', false)
            ->assertJsonPath('data.payment.provider', 'OnoPay')
            ->assertJsonPath('data.payment.state', 'waiting_payment');

        $this
            ->withToken($token)
            ->getJson('/api/appointments')
            ->assertOk()
            ->assertJsonPath('data.0.is_paid', false)
            ->assertJsonPath('data.0.payment.provider', 'OnoPay');
    }

    public function test_mobile_wallet_balance_sync_and_payment_flow(): void
    {
        config(['services.onopay.merchant_phone' => '081111111111']);

        Http::fake([
            'http://onopay.web.id/api/v1/merchant/check-balance' => Http::sequence()
                ->push([
                    'success' => true,
                    'message' => 'Balance ditemukan',
                    'data' => [
                        'phone_number' => '081234567892',
                        'name' => 'Pasien Saldo',
                        'balance' => 0,
                    ],
                ])
                ->push([
                    'success' => true,
                    'message' => 'Balance ditemukan',
                    'data' => [
                        'phone_number' => '081234567892',
                        'name' => 'Pasien Saldo',
                        'balance' => 300000,
                    ],
                ])
                ->push([
                    'success' => true,
                    'message' => 'Balance ditemukan',
                    'data' => [
                        'phone_number' => '081234567892',
                        'name' => 'Pasien Saldo',
                        'balance' => 50000,
                    ],
                ]),
            'http://onopay.web.id/api/v1/payment/qr/generate' => Http::response([
                'success' => true,
                'message' => 'QR code berhasil dibuat',
                'data' => [
                    'qr_code' => 'QR-SMILEDENTAL-1',
                    'amount' => 250000,
                    'merchant_code' => 'SMILEDENTAL',
                    'qr_mode' => 'single_use',
                ],
            ]),
            'http://onopay.web.id/api/v1/payment/qr/pay' => Http::response([
                'success' => true,
                'message' => 'Pembayaran berhasil',
                'data' => [
                    'transaction_id' => 'TXN-PAY-1',
                    'payer_phone' => '081234567892',
                    'receiver_phone' => '081111111111',
                    'amount' => 250000,
                    'payer_new_balance' => 50000,
                    'receiver_new_balance' => 250000,
                    'status' => 'success',
                ],
            ]),
        ]);

        $register = $this->postJson('/api/register', [
            'name' => 'Pasien Saldo',
            'email' => 'saldo@example.test',
            'password' => 'password123',
            'phone' => '081234567892',
        ]);

        $token = $register->json('token');

        $this
            ->withToken($token)
            ->getJson('/api/wallet')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.provider', 'OnoPay')
            ->assertJsonPath('data.balance', 0);

        $this
            ->withToken($token)
            ->getJson('/api/wallet')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.provider', 'OnoPay')
            ->assertJsonPath('data.balance', 300000);

        $doctorId = DB::table('dokter')->insertGetId([
            'nama' => 'drg. Wallet',
            'spesialisasi' => 'Umum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('layanan_klinik')->insert([
            'nama_layanan' => 'Scaling',
            'harga' => 250000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $appointment = $this
            ->withToken($token)
            ->postJson('/api/appointments', [
                'doctor_id' => $doctorId,
                'tanggal' => '2026-06-16',
                'jam' => '10:00',
                'keluhan' => 'Kontrol rutin',
            ]);

        $appointmentId = $appointment->json('data.id');

        $this
            ->withToken($token)
            ->postJson("/api/appointments/{$appointmentId}/pay-with-balance")
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.appointment.is_paid', true)
            ->assertJsonPath('data.wallet.balance', 50000);

        Http::assertSent(fn ($request) => $request->url() === 'http://onopay.web.id/api/v1/payment/qr/pay');
    }

    public function test_mobile_endpoints_require_token(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
        $this->getJson('/api/appointments')->assertUnauthorized();
    }
}

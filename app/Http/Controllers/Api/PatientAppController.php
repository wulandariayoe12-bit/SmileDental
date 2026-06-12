<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OnoPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Validation\ValidationException;

class PatientAppController extends Controller
{
    public function __construct(private readonly OnoPayService $onoPay)
    {
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $this->formatUser($request->user()),
            'patient' => $this->patientFor($request->user()),
        ]);
    }

    public function doctors()
    {
        $doctors = DB::table('dokter')
            ->select('id', 'nama', 'spesialisasi', 'no_hp')
            ->orderBy('dokter.nama')
            ->get()
            ->map(function ($doctor) {
                $schedules = DB::table('jadwal_dokter')
                    ->where('doctor_id', $doctor->id)
                    ->orderBy('hari')
                    ->orderBy('jam_mulai')
                    ->get()
                    ->map(fn ($schedule) => [
                        'hari' => $schedule->hari,
                        'jam_mulai' => substr((string) $schedule->jam_mulai, 0, 5),
                        'jam_selesai' => substr((string) $schedule->jam_selesai, 0, 5),
                    ]);

                $doctor->jadwal = $schedules;
                $doctor->jadwal_label = $schedules->isEmpty()
                    ? 'Jadwal belum tersedia'
                    : $schedules->map(fn ($schedule) => "{$schedule['hari']} {$schedule['jam_mulai']}-{$schedule['jam_selesai']}")->implode(', ');

                return $doctor;
            });

        return response()->json([
            'status' => true,
            'data' => $doctors,
        ]);
    }

    public function services()
    {
        return response()->json([
            'status' => true,
            'data' => DB::table('layanan_klinik')->orderBy('nama_layanan')->get(),
        ]);
    }

    public function clinic()
    {
        $locations = DB::table('clinic_locations')
            ->orderBy('name')
            ->get();

        if ($locations->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data klinik belum tersedia.',
                'data' => null,
            ]);
        }

        $mainLocation = (array) $locations->first();
        $mainLocation['locations'] = $locations;

        return response()->json([
            'status' => true,
            'data' => $mainLocation,
        ]);
    }

    public function schedules(Request $request)
    {
        $query = DB::table('jadwal_dokter')
            ->join('dokter', 'jadwal_dokter.doctor_id', '=', 'dokter.id')
            ->select('jadwal_dokter.*', 'dokter.nama as nama_dokter')
            ->orderBy('jadwal_dokter.hari')
            ->orderBy('jadwal_dokter.jam_mulai');

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->integer('doctor_id'));
        }

        return response()->json([
            'status' => true,
            'data' => $query->get(),
        ]);
    }

    public function appointments(Request $request)
    {
        $patientId = $this->requirePatientId($request);

        $appointments = DB::table('janji_temu')
            ->join('dokter', 'janji_temu.doctor_id', '=', 'dokter.id')
            ->leftJoin('pembayaran', 'janji_temu.id', '=', 'pembayaran.appointment_id')
            ->where('janji_temu.patient_id', $patientId)
            ->select(
                'janji_temu.id',
                'janji_temu.doctor_id',
                'dokter.nama as nama_dokter',
                'dokter.spesialisasi',
                'janji_temu.tanggal',
                'janji_temu.jam',
                'janji_temu.keluhan',
                'janji_temu.status',
                'pembayaran.id as payment_id',
                'pembayaran.total_harga',
                'pembayaran.metode_pembayaran',
                'pembayaran.status as payment_status'
            )
            ->latest('janji_temu.id')
            ->get()
            ->map(fn ($appointment) => $this->formatAppointment($appointment, $request->user()->name));

        return response()->json([
            'status' => true,
            'data' => $appointments,
        ]);
    }

    public function storeAppointment(Request $request)
    {
        $patientId = $this->requirePatientId($request);

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:dokter,id'],
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'keluhan' => ['required', 'string', 'max:1000'],
            'service_id' => ['nullable', 'exists:layanan_klinik,id'],
        ]);

        $hasConflict = DB::table('janji_temu')
            ->where('doctor_id', $validated['doctor_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('jam', $validated['jam'])
            ->where('status', '!=', 'batal')
            ->exists();

        if ($hasConflict) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal dokter pada tanggal dan jam tersebut sudah terisi.',
            ], 422);
        }

        $service = $request->filled('service_id')
            ? DB::table('layanan_klinik')->where('id', $request->integer('service_id'))->first()
            : DB::table('layanan_klinik')->orderBy('id')->first();

        $totalHarga = $service?->harga ?? 250000;

        $appointmentId = DB::transaction(function () use ($patientId, $validated, $totalHarga) {
            $appointmentId = DB::table('janji_temu')->insertGetId([
                'patient_id' => $patientId,
                'doctor_id' => $validated['doctor_id'],
                'tanggal' => $validated['tanggal'],
                'jam' => $validated['jam'],
                'keluhan' => $validated['keluhan'],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('pembayaran')->insert([
                'patient_id' => $patientId,
                'appointment_id' => $appointmentId,
                'total_harga' => $totalHarga,
                'metode_pembayaran' => 'OnoPay',
                'status' => 'belum',
                'tanggal' => $validated['tanggal'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $appointmentId;
        });

        $appointment = DB::table('janji_temu')
            ->join('dokter', 'janji_temu.doctor_id', '=', 'dokter.id')
            ->leftJoin('pembayaran', 'janji_temu.id', '=', 'pembayaran.appointment_id')
            ->where('janji_temu.id', $appointmentId)
            ->select(
                'janji_temu.id',
                'janji_temu.doctor_id',
                'dokter.nama as nama_dokter',
                'dokter.spesialisasi',
                'janji_temu.tanggal',
                'janji_temu.jam',
                'janji_temu.keluhan',
                'janji_temu.status',
                'pembayaran.id as payment_id',
                'pembayaran.total_harga',
                'pembayaran.metode_pembayaran',
                'pembayaran.status as payment_status'
            )
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil disimpan',
            'data' => $this->formatAppointment($appointment, $request->user()->name),
        ], 201);
    }

    public function payAppointment(Request $request, $appointmentId)
    {
        $patientId = $this->requirePatientId($request);

        $validated = $request->validate([
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        $paymentMethod = $validated['payment_method'] ?? 'OnoPay';

        $appointment = DB::table('janji_temu')
            ->join('dokter', 'janji_temu.doctor_id', '=', 'dokter.id')
            ->leftJoin('pembayaran', 'janji_temu.id', '=', 'pembayaran.appointment_id')
            ->where('janji_temu.id', $appointmentId)
            ->where('janji_temu.patient_id', $patientId)
            ->select(
                'janji_temu.id',
                'janji_temu.doctor_id',
                'dokter.nama as nama_dokter',
                'dokter.spesialisasi',
                'janji_temu.tanggal',
                'janji_temu.jam',
                'janji_temu.keluhan',
                'janji_temu.status',
                'pembayaran.id as payment_id',
                'pembayaran.total_harga',
                'pembayaran.metode_pembayaran',
                'pembayaran.status as payment_status'
            )
            ->first();

        abort_if(! $appointment || ! $appointment->payment_id, 404);

        DB::table('pembayaran')
            ->where('appointment_id', $appointmentId)
            ->where('patient_id', $patientId)
            ->update([
                'status' => 'belum',
                'metode_pembayaran' => $paymentMethod,
                'updated_at' => now(),
            ]);

        $appointment->metode_pembayaran = $paymentMethod;
        $appointment->payment_status = 'belum';

        return response()->json([
            'status' => true,
            'message' => "Instruksi pembayaran {$paymentMethod} berhasil dibuat",
            'data' => $this->formatAppointment($appointment, $request->user()->name),
        ]);
    }

    public function wallet(Request $request)
    {
        $patientId = $this->requirePatientId($request);

        return response()->json([
            'status' => true,
            'data' => $this->walletData($patientId),
        ]);
    }

    public function payAppointmentWithBalance(Request $request, $appointmentId)
    {
        $patientId = $this->requirePatientId($request);
        $patientPhone = $this->patientPhone($patientId);
        $merchantPhone = (string) config('services.onopay.merchant_phone', '');

        $payment = DB::table('pembayaran')
            ->where('appointment_id', $appointmentId)
            ->where('patient_id', $patientId)
            ->first();

        abort_if(! $payment, 404);

        if ($payment->status === 'lunas') {
            $appointment = $this->appointmentFor($appointmentId, $patientId);

            return response()->json([
                'status' => true,
                'message' => 'Tagihan sudah lunas',
                'data' => [
                    'appointment' => $this->formatAppointment($appointment, $request->user()->name),
                    'wallet' => $this->walletData($patientId),
                ],
            ]);
        }

        $amount = (int) $payment->total_harga;
        $appointmentBeforePayment = $this->appointmentFor($appointmentId, $patientId);
        $paymentInstruction = $this->onoPay->createQrisInstruction(
            $appointmentBeforePayment,
            $payment,
            $request->user()->name
        );
        $onopayPayment = null;

        if ($merchantPhone !== '') {
            try {
                $onopayPayment = $this->onoPay->payByQr(
                    $merchantPhone,
                    $patientPhone,
                    $amount,
                    'Pembayaran SmileDental appointment #' . $appointmentId
                );
            } catch (Throwable) {
                $onopayPayment = null;
            }
        }

        try {
            DB::transaction(function () use ($patientId, $appointmentId, $payment, $amount, $onopayPayment, $paymentInstruction) {
            $payment = DB::table('pembayaran')
                ->where('id', $payment->id)
                ->lockForUpdate()
                ->first();

            abort_if(! $payment, 404);

            if ($payment->status === 'lunas') {
                return;
            }

            $wallet = $this->walletFor($patientId, true);
            $currentBalance = (int) $wallet->balance;

            if ($currentBalance < $amount) {
                throw ValidationException::withMessages([
                    'balance' => 'Saldo OnoPay tidak cukup untuk membayar tagihan ini.',
                ]);
            }

            $newBalance = (int) data_get($onopayPayment, 'data.payer_new_balance', $currentBalance - $amount);

            DB::table('patient_wallets')
                ->where('id', $wallet->id)
                ->update([
                    'balance' => $newBalance,
                    'updated_at' => now(),
                ]);

            DB::table('wallet_transactions')->insert([
                'patient_id' => $patientId,
                'appointment_id' => $appointmentId,
                'type' => 'payment',
                'provider' => 'OnoPay',
                'amount' => $amount,
                'status' => 'success',
                'reference' => (string) data_get($onopayPayment, 'data.transaction_id', $this->walletReference('PAY', $patientId)),
                'qris_payload' => $paymentInstruction['qris_payload'] ?? null,
                'qris_image_url' => $paymentInstruction['qris_image_url'] ?? null,
                'notes' => $onopayPayment
                    ? 'Pembayaran appointment dari saldo OnoPay web'
                    : 'Pembayaran appointment dari saldo OnoPay lokal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('pembayaran')
                ->where('id', $payment->id)
                ->update([
                    'status' => 'lunas',
                    'metode_pembayaran' => 'Saldo OnoPay',
                    'updated_at' => now(),
                ]);
            });
        } catch (ValidationException $error) {
            throw $error;
        }

        $appointment = $this->appointmentFor($appointmentId, $patientId);

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran berhasil menggunakan saldo OnoPay',
            'data' => [
                'appointment' => $this->formatAppointment($appointment, $request->user()->name),
                'wallet' => $this->walletData($patientId),
                'payment' => [
                    ...$paymentInstruction,
                    'state' => 'paid',
                ],
            ],
        ]);
    }

    public function reviews()
    {
        $reviews = DB::table('doctor_reviews')
            ->join('dokter', 'doctor_reviews.doctor_id', '=', 'dokter.id')
            ->leftJoin('users', 'doctor_reviews.user_id', '=', 'users.id')
            ->leftJoin('pasien', 'doctor_reviews.patient_id', '=', 'pasien.id')
            ->select(
                'doctor_reviews.id',
                'doctor_reviews.rating',
                'doctor_reviews.message',
                'doctor_reviews.created_at',
                'dokter.id as doctor_id',
                'dokter.nama as doctor_name',
                DB::raw("COALESCE(users.name, pasien.nama, 'Pasien') as name")
            )
            ->latest('doctor_reviews.id')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $reviews,
        ]);
    }

    public function storeReview(Request $request)
    {
        $patientId = $this->requirePatientId($request);

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:dokter,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $hasAppointmentWithDoctor = DB::table('janji_temu')
            ->where('patient_id', $patientId)
            ->where('doctor_id', $validated['doctor_id'])
            ->where('status', '!=', 'batal')
            ->exists();

        if (! $hasAppointmentWithDoctor) {
            return response()->json([
                'status' => false,
                'message' => 'Ulasan hanya bisa diberikan untuk dokter yang pernah Anda booking.',
            ], 422);
        }

        $reviewId = DB::table('doctor_reviews')->insertGetId([
            'patient_id' => $patientId,
            'doctor_id' => $validated['doctor_id'],
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'message' => $validated['message'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $review = DB::table('doctor_reviews')
            ->join('dokter', 'doctor_reviews.doctor_id', '=', 'dokter.id')
            ->leftJoin('users', 'doctor_reviews.user_id', '=', 'users.id')
            ->leftJoin('pasien', 'doctor_reviews.patient_id', '=', 'pasien.id')
            ->where('doctor_reviews.id', $reviewId)
            ->select(
                'doctor_reviews.id',
                'doctor_reviews.rating',
                'doctor_reviews.message',
                'doctor_reviews.created_at',
                'dokter.id as doctor_id',
                'dokter.nama as doctor_name',
                DB::raw("COALESCE(users.name, pasien.nama, 'Pasien') as name")
            )
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Ulasan berhasil dikirim',
            'data' => $review,
        ], 201);
    }

    public function medicalRecords(Request $request)
    {
        $patientId = $this->requirePatientId($request);

        $records = DB::table('rekam_medis')
            ->join('dokter', 'rekam_medis.doctor_id', '=', 'dokter.id')
            ->where('rekam_medis.patient_id', $patientId)
            ->select(
                'rekam_medis.id',
                'rekam_medis.appointment_id',
                'rekam_medis.diagnosa',
                'rekam_medis.tindakan',
                'rekam_medis.catatan',
                'rekam_medis.tanggal',
                'dokter.nama as nama_dokter'
            )
            ->latest('rekam_medis.tanggal')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $records,
        ]);
    }

    private function requirePatientId(Request $request): int
    {
        if (! $request->user()->patient_id) {
            abort(response()->json([
                'status' => false,
                'message' => 'Akun ini belum terhubung dengan data pasien.',
            ], 422));
        }

        return (int) $request->user()->patient_id;
    }

    private function patientFor($user)
    {
        if (! $user->patient_id) {
            return null;
        }

        return DB::table('pasien')->where('id', $user->patient_id)->first();
    }

    private function formatUser($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'patient_id' => $user->patient_id,
        ];
    }

    private function appointmentFor($appointmentId, int $patientId)
    {
        $appointment = DB::table('janji_temu')
            ->join('dokter', 'janji_temu.doctor_id', '=', 'dokter.id')
            ->leftJoin('pembayaran', 'janji_temu.id', '=', 'pembayaran.appointment_id')
            ->where('janji_temu.id', $appointmentId)
            ->where('janji_temu.patient_id', $patientId)
            ->select(
                'janji_temu.id',
                'janji_temu.doctor_id',
                'dokter.nama as nama_dokter',
                'dokter.spesialisasi',
                'janji_temu.tanggal',
                'janji_temu.jam',
                'janji_temu.keluhan',
                'janji_temu.status',
                'pembayaran.id as payment_id',
                'pembayaran.total_harga',
                'pembayaran.metode_pembayaran',
                'pembayaran.status as payment_status'
            )
            ->first();

        abort_if(! $appointment, 404);

        return $appointment;
    }

    private function walletData(int $patientId): array
    {
        $wallet = $this->walletFor($patientId);
        $phoneNumber = $this->patientPhone($patientId);
        $syncStatus = 'cached';
        $syncMessage = 'Menampilkan saldo terakhir yang tersimpan.';
        $onopayName = null;

        try {
            $onopayBalance = $this->onoPay->checkBalance($phoneNumber);
            $remoteBalance = (int) data_get($onopayBalance, 'data.balance', $wallet->balance);
            $onopayName = data_get($onopayBalance, 'data.name');
            $syncStatus = 'synced';
            $syncMessage = 'Saldo berhasil disinkronkan dari OnoPay.';

            if ((int) $wallet->balance !== $remoteBalance) {
                DB::table('patient_wallets')
                    ->where('id', $wallet->id)
                    ->update([
                        'balance' => $remoteBalance,
                        'updated_at' => now(),
                    ]);

                $wallet = DB::table('patient_wallets')->where('id', $wallet->id)->first();
            }
        } catch (Throwable $error) {
            $syncStatus = 'failed';
            $syncMessage = $error->getMessage();
        }

        $transactions = DB::table('wallet_transactions')
            ->where('patient_id', $patientId)
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn ($transaction) => $this->formatWalletTransaction($transaction))
            ->values();

        return [
            'provider' => 'OnoPay',
            'currency' => 'IDR',
            'balance' => (int) $wallet->balance,
            'phone_number' => $phoneNumber,
            'onopay_name' => $onopayName,
            'web_url' => $this->onoPay->webUrl(),
            'sync_status' => $syncStatus,
            'sync_message' => $syncMessage,
            'synced_at' => $syncStatus === 'synced' ? now()->toIso8601String() : null,
            'transactions' => $transactions,
        ];
    }

    private function walletFor(int $patientId, bool $lock = false)
    {
        $query = DB::table('patient_wallets')->where('patient_id', $patientId);

        if ($lock) {
            $query->lockForUpdate();
        }

        $wallet = $query->first();

        if ($wallet) {
            return $wallet;
        }

        DB::table('patient_wallets')->insert([
            'patient_id' => $patientId,
            'balance' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $query = DB::table('patient_wallets')->where('patient_id', $patientId);

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    private function formatWalletTransaction($transaction): array
    {
        return [
            'id' => $transaction->id,
            'type' => $transaction->type,
            'provider' => $transaction->provider,
            'amount' => (int) $transaction->amount,
            'status' => $transaction->status,
            'reference' => $transaction->reference,
            'notes' => $transaction->notes,
            'created_at' => $transaction->created_at,
            'expires_at' => $transaction->expires_at,
            'payment' => null,
        ];
    }

    private function walletReference(string $type, int $patientId): string
    {
        return 'ONOPAY-' . $type . '-SD-' . $patientId . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));
    }

    private function patientPhone(int $patientId): string
    {
        $phoneNumber = (string) DB::table('pasien')->where('id', $patientId)->value('no_hp');

        if (trim($phoneNumber) === '') {
            throw ValidationException::withMessages([
                'phone' => 'Nomor HP pasien belum tersedia untuk integrasi OnoPay.',
            ]);
        }

        return $phoneNumber;
    }

    private function formatAppointment($appointment, string $patientName): array
    {
        $isPaid = $appointment->payment_status === 'lunas';

        return [
            'id' => $appointment->id,
            'nama' => $patientName,
            'doctor_id' => $appointment->doctor_id,
            'dokter' => $appointment->nama_dokter,
            'spesialisasi' => $appointment->spesialisasi,
            'tanggal' => $appointment->tanggal,
            'jam' => substr((string) $appointment->jam, 0, 5),
            'keluhan' => $appointment->keluhan ?: '-',
            'status' => $appointment->status,
            'payment_id' => $appointment->payment_id,
            'biaya' => (float) ($appointment->total_harga ?? 0),
            'payment_method' => $appointment->metode_pembayaran ?? 'OnoPay',
            'is_paid' => $isPaid,
            'payment_status' => $appointment->payment_status ?? 'belum',
            'payment' => $isPaid || ! $appointment->payment_id
                ? null
                : $this->onoPay->createQrisInstruction(
                    $appointment,
                    (object) [
                        'id' => $appointment->payment_id,
                        'total_harga' => $appointment->total_harga ?? 0,
                    ],
                    $patientName
                ),
        ];
    }
}

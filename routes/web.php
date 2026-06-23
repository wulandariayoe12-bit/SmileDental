<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\EnsureUserIsLoggedIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download/smiledental-app', function () {
    $appPath = public_path('downloads/smiledental.apk');

    if (! file_exists($appPath)) {
        return redirect('/#download')->with('download_error', 'File aplikasi belum tersedia. Letakkan file APK di public/downloads/smiledental.apk agar tombol download aktif.');
    }

    return response()->download($appPath, 'SmileDental.apk');
})->name('download.app');

Route::get('/app', function () {
    return Session::has('user_id') ? redirect('/dashboard') : redirect('/login');
});

Route::get('/login', function () {
    return Session::has('user_id') ? redirect('/dashboard') : view('pages.login');
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = DB::table('users')
        ->where('email', $request->email)
        ->first();

    if ($user && Hash::check($request->password, $user->password)) {
        $request->session()->regenerate();

        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('role', $user->role);

        return redirect('/dashboard');
    }

    return back()->withInput($request->only('email'))->with('error', 'Email atau Password Salah');
});

Route::get('/register', function () {
    return Session::has('user_id') ? redirect('/dashboard') : view('pages.register');
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8'],
    ]);

    DB::table('users')->insert([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'pasien',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
});

Route::middleware(EnsureUserIsLoggedIn::class)->group(function () {
    Route::post('/logout', function (Request $request) {
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    });

    Route::get('/dashboard', function () {
        $jumlahPasien = DB::table('pasien')->count();
        $jumlahDokter = DB::table('dokter')->count();
        $jumlahLayanan = DB::table('layanan_klinik')->count();
        $jumlahJanjiTemu = DB::table('janji_temu')->count();
        $totalPendapatan = DB::table('pembayaran')->sum('total_harga');

        $appointments = DB::table('janji_temu')
            ->join('pasien', 'janji_temu.patient_id', '=', 'pasien.id')
            ->join('dokter', 'janji_temu.doctor_id', '=', 'dokter.id')
            ->select(
                'janji_temu.*',
                'pasien.nama as nama_pasien',
                'dokter.nama as nama_dokter'
            )
            ->latest('janji_temu.id')
            ->limit(5)
            ->get();

        $chartData = DB::table('janji_temu')
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('pages.dashboard', compact(
            'jumlahPasien',
            'jumlahDokter',
            'jumlahLayanan',
            'jumlahJanjiTemu',
            'totalPendapatan',
            'appointments',
            'chartData'
        ));
    });

    Route::get('/dashboard/realtime', function () {
        $appointments = DB::table('janji_temu')
            ->join('pasien', 'janji_temu.patient_id', '=', 'pasien.id')
            ->join('dokter', 'janji_temu.doctor_id', '=', 'dokter.id')
            ->select(
                'janji_temu.*',
                'pasien.nama as nama_pasien',
                'dokter.nama as nama_dokter'
            )
            ->latest('janji_temu.id')
            ->limit(5)
            ->get();

        $chartData = DB::table('janji_temu')
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return response()->json([
            'counts' => [
                'patients' => DB::table('pasien')->count(),
                'doctors' => DB::table('dokter')->count(),
                'services' => DB::table('layanan_klinik')->count(),
                'appointments' => DB::table('janji_temu')->count(),
                'revenue' => DB::table('pembayaran')->sum('total_harga'),
                'todayAppointments' => DB::table('janji_temu')->whereDate('tanggal', today())->count(),
                'pendingAppointments' => DB::table('janji_temu')->where('status', 'pending')->count(),
            ],
            'appointments' => $appointments,
            'chartData' => $chartData,
            'serverTime' => now()->format('d M Y H:i:s'),
        ]);
    });

    Route::get('/patients', [PatientController::class, 'index']);
    Route::get('/patients/create', [PatientController::class, 'create']);
    Route::post('/patients/store', [PatientController::class, 'store']);
    Route::get('/patients/edit/{id}', [PatientController::class, 'edit']);
    Route::post('/patients/update/{id}', [PatientController::class, 'update']);
    Route::delete('/patients/{id}', [PatientController::class, 'destroy']);

    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/create', [DoctorController::class, 'create']);
    Route::post('/doctors/store', [DoctorController::class, 'store']);
    Route::get('/doctors/edit/{id}', [DoctorController::class, 'edit']);
    Route::post('/doctors/update/{id}', [DoctorController::class, 'update']);
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);

    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/create', [ServiceController::class, 'create']);
    Route::post('/services/store', [ServiceController::class, 'store']);
    Route::get('/services/edit/{id}', [ServiceController::class, 'edit']);
    Route::post('/services/update/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::get('/appointments/create', [AppointmentController::class, 'create']);
    Route::post('/appointments/store', [AppointmentController::class, 'store']);
    Route::get('/appointments/edit/{id}', [AppointmentController::class, 'edit']);
    Route::post('/appointments/update/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

    Route::get('/medical-records', [MedicalRecordController::class, 'index']);
    Route::get('/medical-records/create', [MedicalRecordController::class, 'create']);
    Route::post('/medical-records/store', [MedicalRecordController::class, 'store']);
    Route::get('/medical-records/edit/{id}', [MedicalRecordController::class, 'edit']);
    Route::post('/medical-records/update/{id}', [MedicalRecordController::class, 'update']);
    Route::delete('/medical-records/{id}', [MedicalRecordController::class, 'destroy']);

    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/create', [PaymentController::class, 'create']);
    Route::post('/payments/store', [PaymentController::class, 'store']);
    Route::get('/payments/edit/{id}', [PaymentController::class, 'edit']);
    Route::post('/payments/update/{id}', [PaymentController::class, 'update']);
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);

    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/schedules/create', [ScheduleController::class, 'create']);
    Route::post('/schedules/store', [ScheduleController::class, 'store']);
    Route::get('/schedules/edit/{id}', [ScheduleController::class, 'edit']);
    Route::post('/schedules/update/{id}', [ScheduleController::class, 'update']);
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
    Route::get('/profile/{id}', [PatientController::class, 'profile']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientAppController;

Route::get('/test', function () {
    return response()->json([
        'status' => true,
        'message' => 'API Laravel Berhasil'
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [PatientAppController::class, 'me']);
    Route::get('/doctors', [PatientAppController::class, 'doctors']);
    Route::get('/services', [PatientAppController::class, 'services']);
    Route::get('/clinic', [PatientAppController::class, 'clinic']);
    Route::get('/schedules', [PatientAppController::class, 'schedules']);
    Route::get('/appointments', [PatientAppController::class, 'appointments']);
    Route::post('/appointments', [PatientAppController::class, 'storeAppointment']);
    Route::post('/appointments/{appointment}/pay', [PatientAppController::class, 'payAppointment']);
    Route::post('/appointments/{appointment}/pay-with-balance', [PatientAppController::class, 'payAppointmentWithBalance']);
    Route::get('/wallet', [PatientAppController::class, 'wallet']);
    Route::get('/medical-records', [PatientAppController::class, 'medicalRecords']);
    Route::get('/reviews', [PatientAppController::class, 'reviews']);
    Route::post('/reviews', [PatientAppController::class, 'storeReview']);
});

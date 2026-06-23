<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (! Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {

            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'token' => $user->createToken('smile-dental-mobile')->plainTextToken,
            'user' => $this->formatUser($user),
            'patient' => $this->patientFor($user),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'required|string|max:30',
        ]);

        $phone = $this->normalizeOnoPayPhone((string) $request->phone);

        if (! preg_match('/^08[0-9]{8,13}$/', $phone)) {
            return response()->json([
                'status' => false,
                'message' => 'Nomor HP wajib diisi dengan nomor OnoPay aktif, diawali 08, dan berisi 10-15 digit.',
                'errors' => [
                    'phone' => ['Nomor HP wajib diawali 08 dan berisi angka saja.'],
                ],
            ], 422);
        }

        $user = DB::transaction(function () use ($request, $phone) {
            $patientId = DB::table('pasien')->insertGetId([
                'nama' => $request->name,
                'no_hp' => $phone,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien',
                'patient_id' => $patientId,
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'token' => $user->createToken('smile-dental-mobile')->plainTextToken,
            'user' => $this->formatUser($user),
            'patient' => $this->patientFor($user),
        ], 201);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $patient = $this->patientFor($user);

        return response()->json([
            'status' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'patient' => $patient ? [
                'id' => $patient->id,
                'nama' => $patient->nama,
                'no_hp' => $patient->no_hp,
            ] : null,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    private function patientFor(User $user)
    {
        if (! $user->patient_id) {
            return null;
        }

        return DB::table('pasien')->where('id', $user->patient_id)->first();
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'patient_id' => $user->patient_id,
        ];
    }

    private function normalizeOnoPayPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '62')) {
            return '0' . substr($digits, 2);
        }

        return $digits;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    // READ
    public function index()
    {
        $patients = DB::table('pasien')->get();

        return view('pages.patients.index', compact('patients'));
    }

    // CREATE (form)
    public function create()
    {
        return view('pages.patients.create');
    }

    // STORE (simpan)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'riwayat_penyakit' => ['nullable', 'string'],
        ]);

        DB::table('pasien')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/patients');
    }

    // EDIT (form)
    public function edit($id)
    {
        $patient = DB::table('pasien')->where('id', $id)->first();

        abort_if(! $patient, 404);

        return view('pages.patients.edit', compact('patient'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'riwayat_penyakit' => ['nullable', 'string'],
        ]);

        $updated = DB::table('pasien')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        abort_if($updated === 0 && ! DB::table('pasien')->where('id', $id)->exists(), 404);

        return redirect('/patients');
    }

    // DELETE
    public function destroy($id)
    {
        abort_if(DB::table('pasien')->where('id', $id)->delete() === 0, 404);

        return redirect('/patients');
    }

    // PROFILE
    public function profile($id)
    {
        $patient = DB::table('pasien')
            ->where('id', $id)
            ->first();

        abort_if(!$patient, 404);

        $records = DB::table('rekam_medis')
            ->leftJoin('dokter', 'rekam_medis.doctor_id', '=', 'dokter.id')
            ->where('rekam_medis.patient_id', $id)
            ->select(
                'rekam_medis.*',
                'dokter.nama as nama_dokter'
            )
            ->orderByDesc('tanggal')
            ->get();

        return view('pages.profile', compact(
            'patient',
            'records'
        ));
    }
}
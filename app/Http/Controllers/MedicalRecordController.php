<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = DB::table('rekam_medis')
            ->leftJoin('pasien', 'rekam_medis.patient_id', '=', 'pasien.id')
            ->leftJoin('dokter', 'rekam_medis.doctor_id', '=', 'dokter.id')
            ->select(
                'rekam_medis.*',
                'pasien.nama as nama_pasien',
                'dokter.nama as nama_dokter'
            )
            ->get();

        return view('pages.medical_records.index', compact('records'));
    }
    public function create()
    {
        $patients = DB::table('pasien')->get();
        $doctors = DB::table('dokter')->get();
        $appointments = DB::table('janji_temu')->get();

        return view('pages.medical_records.create', compact(
            'patients',
            'doctors',
            'appointments'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:pasien,id'],
            'doctor_id' => ['required', 'exists:dokter,id'],
            'appointment_id' => ['nullable', 'exists:janji_temu,id'],
            'diagnosa' => ['required', 'string', 'max:255'],
            'tindakan' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
        ]);

        DB::table('rekam_medis')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/medical-records');
    }

    public function edit($id)
    {
        $record = DB::table('rekam_medis')->where('id', $id)->first();

        abort_if(! $record, 404);

        $patients = DB::table('pasien')->get();
        $doctors = DB::table('dokter')->get();
        $appointments = DB::table('janji_temu')->get();

        return view(
            'pages.medical_records.edit',
            compact('record', 'patients', 'doctors', 'appointments')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:pasien,id'],
            'doctor_id' => ['required', 'exists:dokter,id'],
            'appointment_id' => ['nullable', 'exists:janji_temu,id'],
            'diagnosa' => ['required', 'string', 'max:255'],
            'tindakan' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
        ]);

        $updated = DB::table('rekam_medis')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        abort_if($updated === 0 && ! DB::table('rekam_medis')->where('id', $id)->exists(), 404);

        return redirect('/medical-records');
    }

    public function destroy($id)
    {
        abort_if(DB::table('rekam_medis')->where('id', $id)->delete() === 0, 404);

        return redirect('/medical-records');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = DB::table('janji_temu')
            ->join('pasien', 'janji_temu.patient_id', '=', 'pasien.id')
            ->join('dokter', 'janji_temu.doctor_id', '=', 'dokter.id')
            ->select(
                'janji_temu.*',
                'pasien.nama as nama_pasien',
                'dokter.nama as nama_dokter'
            )
            ->get();

        return view('pages.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = DB::table('pasien')->get();
        $doctors = DB::table('dokter')->get();

        return view('pages.appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:pasien,id'],
            'doctor_id' => ['required', 'exists:dokter,id'],
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:pending,selesai,batal'],
        ]);

        if ($this->hasScheduleConflict($validated['doctor_id'], $validated['tanggal'], $validated['jam'])) {
            return back()
                ->withInput()
                ->withErrors(['jam' => 'Jadwal dokter pada tanggal dan jam tersebut sudah terisi.']);
        }

        DB::table('janji_temu')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/appointments');
    }

    public function edit($id)
    {
        $appointment = DB::table('janji_temu')
            ->where('id', $id)
            ->first();

        abort_if(! $appointment, 404);

        $patients = DB::table('pasien')->get();
        $doctors = DB::table('dokter')->get();

        return view(
            'pages.appointments.edit',
            compact('appointment', 'patients', 'doctors')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:pasien,id'],
            'doctor_id' => ['required', 'exists:dokter,id'],
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:pending,selesai,batal'],
        ]);

        abort_if(! DB::table('janji_temu')->where('id', $id)->exists(), 404);

        if ($this->hasScheduleConflict($validated['doctor_id'], $validated['tanggal'], $validated['jam'], $id)) {
            return back()
                ->withInput()
                ->withErrors(['jam' => 'Jadwal dokter pada tanggal dan jam tersebut sudah terisi.']);
        }

        DB::table('janji_temu')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        return redirect('/appointments');
    }

    public function destroy($id)
    {
        abort_if(DB::table('janji_temu')->where('id', $id)->delete() === 0, 404);

        return redirect('/appointments');
    }

    private function hasScheduleConflict($doctorId, $tanggal, $jam, $ignoreId = null): bool
    {
        $query = DB::table('janji_temu')
            ->where('doctor_id', $doctorId)
            ->where('tanggal', $tanggal)
            ->where('jam', $jam)
            ->where('status', '!=', 'batal');

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}

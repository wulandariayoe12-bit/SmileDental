<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = DB::table('jadwal_dokter')
            ->join('dokter', 'jadwal_dokter.doctor_id', '=', 'dokter.id')
            ->select(
                'jadwal_dokter.*',
                'dokter.nama as nama_dokter'
            )
            ->get();

        return view('pages.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $doctors = DB::table('dokter')->get();

        return view('pages.schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:dokter,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        DB::table('jadwal_dokter')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/schedules');
    }

    public function edit($id)
    {
        $schedule = DB::table('jadwal_dokter')
            ->where('id', $id)
            ->first();

        abort_if(! $schedule, 404);

        $doctors = DB::table('dokter')->get();

        return view(
            'pages.schedules.edit',
            compact('schedule', 'doctors')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:dokter,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        $updated = DB::table('jadwal_dokter')
            ->where('id', $id)
            ->update($validated + [
                'updated_at' => now(),
            ]);

        abort_if($updated === 0 && ! DB::table('jadwal_dokter')->where('id', $id)->exists(), 404);

        return redirect('/schedules');
    }

    public function destroy($id)
    {
        abort_if(DB::table('jadwal_dokter')
            ->where('id', $id)
            ->delete() === 0, 404);

        return redirect('/schedules');
    }
}

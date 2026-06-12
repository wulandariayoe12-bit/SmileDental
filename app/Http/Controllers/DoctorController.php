<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    // READ
    public function index()
    {
        $doctors = DB::table('dokter')->get();

        return view('pages.doctors.index', compact('doctors'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('pages.doctors.create');
    }

    // SIMPAN
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'spesialisasi' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
        ]);

        DB::table('dokter')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/doctors');
    }

    // FORM EDIT
    public function edit($id)
    {
        $doctor = DB::table('dokter')->where('id', $id)->first();

        abort_if(! $doctor, 404);

        return view('pages.doctors.edit', compact('doctor'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'spesialisasi' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
        ]);

        $updated = DB::table('dokter')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        abort_if($updated === 0 && ! DB::table('dokter')->where('id', $id)->exists(), 404);

        return redirect('/doctors');
    }

    // DELETE
    public function destroy($id)
    {
        abort_if(DB::table('dokter')->where('id', $id)->delete() === 0, 404);

        return redirect('/doctors');
    }
}

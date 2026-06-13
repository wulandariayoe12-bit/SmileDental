<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $services = DB::table('layanan_klinik')->get();

        return view('pages.services.index', compact('services'));
    }

    public function create()
    {
        return view('pages.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'integer', 'min:0'],
        ]);

        DB::table('layanan_klinik')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/services');
    }

    public function edit($id)
    {
        $service = DB::table('layanan_klinik')->where('id', $id)->first();

        abort_if(! $service, 404);

        return view('pages.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'integer', 'min:0'],
        ]);

        $updated = DB::table('layanan_klinik')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        abort_if($updated === 0 && ! DB::table('layanan_klinik')->where('id', $id)->exists(), 404);

        return redirect('/services');
    }

    public function destroy($id)
    {
        abort_if(DB::table('layanan_klinik')->where('id', $id)->delete() === 0, 404);

        return redirect('/services');
    }
}

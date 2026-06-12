<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = DB::table('pembayaran')
            ->join('pasien', 'pembayaran.patient_id', '=', 'pasien.id')
            ->leftJoin('janji_temu', 'pembayaran.appointment_id', '=', 'janji_temu.id')
            ->select(
                'pembayaran.*',
                'pasien.nama as nama_pasien'
            )
            ->get();

        return view('pages.payments.index', compact('payments'));
    }

    public function create()
    {
        $patients = DB::table('pasien')->get();
        $appointments = DB::table('janji_temu')->get();

        return view('pages.payments.create', compact(
            'patients',
            'appointments'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:pasien,id'],
            'appointment_id' => ['nullable', 'exists:janji_temu,id'],
            'total_harga' => ['required', 'integer', 'min:0'],
            'metode_pembayaran' => ['required', 'in:Cash,Transfer,E-Wallet'],
            'status' => ['required', 'in:lunas,belum'],
            'tanggal' => ['required', 'date'],
        ]);

        DB::table('pembayaran')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/payments');
    }

    public function edit($id)
    {
        $payment = DB::table('pembayaran')
            ->where('id', $id)
            ->first();

        abort_if(! $payment, 404);

        $patients = DB::table('pasien')->get();
        $appointments = DB::table('janji_temu')->get();

        return view(
            'pages.payments.edit',
            compact('payment', 'patients', 'appointments')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:pasien,id'],
            'appointment_id' => ['nullable', 'exists:janji_temu,id'],
            'total_harga' => ['required', 'integer', 'min:0'],
            'metode_pembayaran' => ['required', 'in:Cash,Transfer,E-Wallet'],
            'status' => ['required', 'in:lunas,belum'],
            'tanggal' => ['required', 'date'],
        ]);

        $updated = DB::table('pembayaran')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        abort_if($updated === 0 && ! DB::table('pembayaran')->where('id', $id)->exists(), 404);

        return redirect('/payments');
    }

    public function destroy($id)
    {
        abort_if(DB::table('pembayaran')->where('id', $id)->delete() === 0, 404);

        return redirect('/payments');
    }
}

@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Booking Appointment</h2>

<div class="bg-white p-6 rounded shadow w-1/2">
    <form>
        <label>Nama Dokter</label>
        <select class="w-full mb-3 p-2 border rounded">
            <option>Drg. Andi</option>
            <option>Drg. Siti</option>
        </select>

        <label>Tanggal</label>
        <input type="date" class="w-full mb-3 p-2 border rounded">

        <label>Jam</label>
        <input type="time" class="w-full mb-3 p-2 border rounded">

        <label>Keluhan</label>
        <textarea class="w-full mb-3 p-2 border rounded"></textarea>

        <button class="bg-green-500 text-white px-4 py-2 rounded">
            Konfirmasi
        </button>
    </form>
</div>
@endsection
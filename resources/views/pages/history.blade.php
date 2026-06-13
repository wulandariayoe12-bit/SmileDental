@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Riwayat Perawatan</h2>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-blue-500 text-white">
        <tr>
            <th class="p-2">Tanggal</th>
            <th>Dokter</th>
            <th>Perawatan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <td class="p-2">2026-05-01</td>
            <td>Drg. Andi</td>
            <td>Scaling</td>
            <td>Selesai</td>
        </tr>
    </tbody>
</table>
@endsection
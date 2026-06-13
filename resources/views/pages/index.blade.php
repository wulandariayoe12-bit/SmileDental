@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Data Pasien</h2>

<a href="/patients/create" class="bg-blue-500 text-white px-4 py-2 rounded">
    Tambah Pasien
</a>

<table class="w-full mt-4 bg-white shadow">
    <tr class="bg-gray-200">
        <th>Nama</th>
        <th>Email</th>
        <th>Telepon</th>
        <th>Aksi</th>
    </tr>

    @foreach($patients as $p)
    <tr class="text-center">
        <td>{{ $p->name }}</td>
        <td>{{ $p->email }}</td>
        <td>{{ $p->phone }}</td>
        <td>
            <a href="/patients/edit/{{ $p->id }}" class="text-blue-500">Edit</a> |
            <form method="POST" action="/patients/{{ $p->id }}" class="d-inline" onsubmit="return confirm('Hapus pasien ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-link text-danger p-0">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection

@extends('layouts.app')

@section('content')
<h2 class="text-xl mb-4">Tambah Pasien</h2>

<form action="/patients/store" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Nama"
        class="block mb-3 p-2 border w-1/2">

    <input type="email" name="email" placeholder="Email"
        class="block mb-3 p-2 border w-1/2">

    <input type="text" name="phone" placeholder="Telepon"
        class="block mb-3 p-2 border w-1/2">

    <button class="bg-green-500 text-white px-4 py-2">
        Simpan
    </button>
</form>
@endsection
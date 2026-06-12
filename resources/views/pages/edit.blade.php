@extends('layouts.app')

@section('content')
<h2 class="text-xl mb-4">Edit Pasien</h2>

<form action="/patients/update/{{ $patient->id }}" method="POST">
    @csrf

    <input type="text" name="name" value="{{ $patient->name }}"
        class="block mb-3 p-2 border w-1/2">

    <input type="email" name="email" value="{{ $patient->email }}"
        class="block mb-3 p-2 border w-1/2">

    <input type="text" name="phone" value="{{ $patient->phone }}"
        class="block mb-3 p-2 border w-1/2">

    <button class="bg-blue-500 text-white px-4 py-2">
        Update
    </button>
</form>
@endsection
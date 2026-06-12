@extends('layouts.app')

@section('title', 'Edit Pasien - SmileDental')
@section('page_title', 'Edit Pasien')
@section('page_subtitle', 'Perbarui identitas dan riwayat pasien.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">{{ $patient->nama }}</h3>
            <a href="/patients" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/patients/update/{{ $patient->id }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" value="{{ $patient->nama }}" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ $patient->tanggal_lahir }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="L" {{ $patient->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $patient->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No HP</label>
                    <input type="text" name="no_hp" value="{{ $patient->no_hp }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ $patient->alamat }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Riwayat Penyakit</label>
                    <textarea name="riwayat_penyakit" class="form-control" rows="3">{{ $patient->riwayat_penyakit }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/patients" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

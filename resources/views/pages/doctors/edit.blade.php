@extends('layouts.app')

@section('title', 'Edit Dokter - SmileDental')
@section('page_title', 'Edit Dokter')
@section('page_subtitle', 'Perbarui profil dokter klinik.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">{{ $doctor->nama }}</h3>
            <a href="/doctors" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/doctors/update/{{ $doctor->id }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" value="{{ $doctor->nama }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Spesialisasi</label>
                    <input type="text" name="spesialisasi" value="{{ $doctor->spesialisasi }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">No HP</label>
                    <input type="text" name="no_hp" value="{{ $doctor->no_hp }}" class="form-control">
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/doctors" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

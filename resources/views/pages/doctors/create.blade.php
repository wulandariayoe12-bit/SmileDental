@extends('layouts.app')

@section('title', 'Tambah Dokter - SmileDental')
@section('page_title', 'Tambah Dokter')
@section('page_subtitle', 'Tambahkan profil dokter dan spesialisasinya.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Form Dokter</h3>
            <a href="/doctors" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/doctors/store" method="POST" class="row g-3">
                @csrf
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Spesialisasi</label>
                    <input type="text" name="spesialisasi" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">No HP</label>
                    <input type="text" name="no_hp" class="form-control">
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/doctors" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

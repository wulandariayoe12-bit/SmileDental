@extends('layouts.app')

@section('title', 'Tambah Pasien - SmileDental')
@section('page_title', 'Tambah Pasien')
@section('page_subtitle', 'Lengkapi data dasar pasien baru.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Form Pasien</h3>
            <a href="/patients" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/patients/store" method="POST" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No HP</label>
                    <input type="text" name="no_hp" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Riwayat Penyakit</label>
                    <textarea name="riwayat_penyakit" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/patients" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

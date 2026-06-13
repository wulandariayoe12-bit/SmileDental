@extends('layouts.app')

@section('title', 'Edit Layanan - SmileDental')
@section('page_title', 'Edit Layanan')
@section('page_subtitle', 'Perbarui layanan dan estimasi biaya.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">{{ $service->nama_layanan }}</h3>
            <a href="/services" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/services/update/{{ $service->id }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Layanan</label>
                    <input type="text" name="nama_layanan" value="{{ $service->nama_layanan }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Harga</label>
                    <input type="number" name="harga" value="{{ $service->harga }}" class="form-control" min="0" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ $service->deskripsi }}</textarea>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/services" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

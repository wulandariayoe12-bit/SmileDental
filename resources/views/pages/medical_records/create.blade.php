@extends('layouts.app')

@section('title', 'Tambah Rekam Medis - SmileDental')
@section('page_title', 'Tambah Rekam Medis')
@section('page_subtitle', 'Catat diagnosa dan tindakan perawatan.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Form Rekam Medis</h3>
            <a href="/medical-records" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form method="POST" action="/medical-records/store" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pasien</label>
                    <select name="patient_id" class="form-select" required>
                        @foreach ($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Dokter</label>
                    <select name="doctor_id" class="form-select" required>
                        @foreach ($doctors as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Janji Temu</label>
                    <select name="appointment_id" class="form-select">
                        <option value="">Tanpa janji temu</option>
                        @foreach ($appointments as $a)
                            <option value="{{ $a->id }}">#{{ $a->id }} - {{ $a->tanggal }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Diagnosa</label>
                    <input type="text" name="diagnosa" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tindakan</label>
                    <input type="text" name="tindakan" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="4"></textarea>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/medical-records" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

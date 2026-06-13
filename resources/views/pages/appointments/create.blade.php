@extends('layouts.app')

@section('title', 'Tambah Janji Temu - SmileDental')
@section('page_title', 'Tambah Janji Temu')
@section('page_subtitle', 'Jadwalkan kunjungan pasien dengan dokter.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Form Janji Temu</h3>
            <a href="/appointments" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/appointments/store" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pasien</label>
                    <select name="patient_id" class="form-select" required>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dokter</label>
                    <select name="doctor_id" class="form-select" required>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->nama }} - {{ $doctor->spesialisasi ?: 'Umum' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam</label>
                    <input type="time" name="jam" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/appointments" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

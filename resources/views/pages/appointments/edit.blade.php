@extends('layouts.app')

@section('title', 'Edit Janji Temu - SmileDental')
@section('page_title', 'Edit Janji Temu')
@section('page_subtitle', 'Perbarui jadwal dan status kunjungan.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Janji Temu #{{ $appointment->id }}</h3>
            <a href="/appointments" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/appointments/update/{{ $appointment->id }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pasien</label>
                    <select name="patient_id" class="form-select" required>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dokter</label>
                    <select name="doctor_id" class="form-select" required>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->nama }} - {{ $doctor->spesialisasi ?: 'Umum' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $appointment->tanggal }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam</label>
                    <input type="time" name="jam" value="{{ substr($appointment->jam, 0, 5) }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="selesai" {{ $appointment->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ $appointment->status == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/appointments" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

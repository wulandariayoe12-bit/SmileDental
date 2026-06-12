@extends('layouts.app')

@section('title', 'Edit Rekam Medis - SmileDental')
@section('page_title', 'Edit Rekam Medis')
@section('page_subtitle', 'Perbarui diagnosa, tindakan, dan catatan.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Rekam Medis #{{ $record->id }}</h3>
            <a href="/medical-records" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/medical-records/update/{{ $record->id }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pasien</label>
                    <select name="patient_id" class="form-select" required>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $record->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Dokter</label>
                    <select name="doctor_id" class="form-select" required>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $record->doctor_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Janji Temu</label>
                    <select name="appointment_id" class="form-select">
                        <option value="">Tanpa janji temu</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ $record->appointment_id == $appointment->id ? 'selected' : '' }}>#{{ $appointment->id }} - {{ $appointment->tanggal }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Diagnosa</label>
                    <input type="text" name="diagnosa" value="{{ $record->diagnosa }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tindakan</label>
                    <input type="text" name="tindakan" value="{{ $record->tindakan }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $record->tanggal }}" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="4">{{ $record->catatan }}</textarea>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/medical-records" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

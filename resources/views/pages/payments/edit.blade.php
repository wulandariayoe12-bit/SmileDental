@extends('layouts.app')

@section('title', 'Edit Pembayaran - SmileDental')
@section('page_title', 'Edit Pembayaran')
@section('page_subtitle', 'Perbarui detail transaksi pasien.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Pembayaran #{{ $payment->id }}</h3>
            <a href="/payments" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/payments/update/{{ $payment->id }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pasien</label>
                    <select name="patient_id" class="form-select" required>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $payment->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Janji Temu</label>
                    <select name="appointment_id" class="form-select">
                        <option value="">Tanpa janji temu</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ $payment->appointment_id == $appointment->id ? 'selected' : '' }}>#{{ $appointment->id }} - {{ $appointment->tanggal }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Harga</label>
                    <input type="number" name="total_harga" value="{{ $payment->total_harga }}" class="form-control" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Metode</label>
                    <select name="metode_pembayaran" class="form-select">
                        @foreach (['Cash', 'Transfer', 'E-Wallet'] as $method)
                            <option value="{{ $method }}" {{ strtolower($payment->metode_pembayaran) == strtolower($method) ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="lunas" {{ $payment->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum" {{ $payment->status == 'belum' ? 'selected' : '' }}>Belum</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $payment->tanggal }}" class="form-control" required>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/payments" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

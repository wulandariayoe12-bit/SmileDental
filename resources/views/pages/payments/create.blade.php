@extends('layouts.app')

@section('title', 'Tambah Pembayaran - SmileDental')
@section('page_title', 'Tambah Pembayaran')
@section('page_subtitle', 'Catat transaksi pembayaran pasien.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <h3 class="h5 fw-bold mb-0">Form Pembayaran</h3>
            <a href="/payments" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
        <div class="sd-card-body">
            <form action="/payments/store" method="POST" class="row g-3">
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
                    <label class="form-label fw-semibold">Janji Temu</label>
                    <select name="appointment_id" class="form-select">
                        <option value="">Tanpa janji temu</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}">#{{ $appointment->id }} - {{ $appointment->tanggal }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Harga</label>
                    <input type="number" name="total_harga" class="form-control" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Metode</label>
                    <select name="metode_pembayaran" class="form-select">
                        <option value="Cash">Cash</option>
                        <option value="Transfer">Transfer</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="lunas">Lunas</option>
                        <option value="belum">Belum</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="/payments" class="btn btn-outline-secondary">Batal</a>
                    <button class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

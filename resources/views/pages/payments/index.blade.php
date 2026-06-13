@extends('layouts.app')

@section('title', 'Pembayaran - SmileDental')
@section('page_title', 'Pembayaran')
@section('page_subtitle', 'Catat transaksi dan status pembayaran pasien.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Daftar Pembayaran</h3>
                <small class="text-secondary">{{ $payments->count() }} transaksi tercatat</small>
            </div>
            <a href="/payments/create" class="btn btn-primary"><i class="bi bi-credit-card-fill me-2"></i>Tambah Pembayaran</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>Total Harga</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="fw-semibold">{{ $payment->nama_pasien }}</td>
                            <td>Rp {{ number_format($payment->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $payment->metode_pembayaran }}</td>
                            <td><span class="status-badge status-{{ $payment->status }}">{{ $payment->status }}</span></td>
                            <td>{{ $payment->tanggal }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="/payments/edit/{{ $payment->id }}"><i class="bi bi-pencil-square"></i></a>
                                <form class="d-inline" method="POST" action="/payments/{{ $payment->id }}" onsubmit="return confirm('Hapus pembayaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

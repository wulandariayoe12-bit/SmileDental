@extends('layouts.app')

@section('title', 'Layanan Klinik - SmileDental')
@section('page_title', 'Layanan Klinik')
@section('page_subtitle', 'Atur katalog layanan dan estimasi biaya perawatan.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Daftar Layanan</h3>
                <small class="text-secondary">{{ $services->count() }} layanan aktif</small>
            </div>
            <a href="/services/create" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Layanan</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td class="fw-semibold">{{ $service->nama_layanan }}</td>
                            <td>{{ $service->deskripsi ?: '-' }}</td>
                            <td>Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="/services/edit/{{ $service->id }}"><i class="bi bi-pencil-square"></i></a>
                                <form class="d-inline" method="POST" action="/services/{{ $service->id }}" onsubmit="return confirm('Hapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">Belum ada data layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

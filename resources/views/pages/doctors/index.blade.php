@extends('layouts.app')

@section('title', 'Data Dokter - SmileDental')
@section('page_title', 'Data Dokter')
@section('page_subtitle', 'Kelola dokter, spesialisasi, dan kontak klinik.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Daftar Dokter</h3>
                <small class="text-secondary">{{ $doctors->count() }} dokter tersedia</small>
            </div>
            <a href="/doctors/create" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Dokter</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Spesialisasi</th>
                        <th>No HP</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($doctors as $doctor)
                        <tr>
                            <td class="fw-semibold">{{ $doctor->nama }}</td>
                            <td>{{ $doctor->spesialisasi ?: '-' }}</td>
                            <td>{{ $doctor->no_hp ?: '-' }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="/doctors/edit/{{ $doctor->id }}"><i class="bi bi-pencil-square"></i></a>
                                <form class="d-inline" method="POST" action="/doctors/{{ $doctor->id }}" onsubmit="return confirm('Hapus dokter ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">Belum ada data dokter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

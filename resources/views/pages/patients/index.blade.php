@extends('layouts.app')

@section('title', 'Data Pasien - SmileDental')
@section('page_title', 'Data Pasien')
@section('page_subtitle', 'Kelola identitas, kontak, dan riwayat kesehatan pasien.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Daftar Pasien</h3>
                <small class="text-secondary">{{ $patients->count() }} pasien terdaftar</small>
            </div>
            <a href="/patients/create" class="btn btn-primary"><i class="bi bi-person-plus-fill me-2"></i>Tambah Pasien</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>No HP</th>
                        <th>Riwayat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr>
                            <td class="fw-semibold">{{ $patient->nama }}</td>
                            <td>{{ $patient->tanggal_lahir ?: '-' }}</td>
                            <td>{{ $patient->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}</td>
                            <td>{{ $patient->no_hp ?: '-' }}</td>
                            <td>{{ $patient->riwayat_penyakit ?: '-' }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="/patients/edit/{{ $patient->id }}"><i class="bi bi-pencil-square"></i></a>
                                <form class="d-inline" method="POST" action="/patients/{{ $patient->id }}" onsubmit="return confirm('Hapus pasien ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">Belum ada data pasien.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

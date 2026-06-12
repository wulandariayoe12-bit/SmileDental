@extends('layouts.app')

@section('title', 'Janji Temu - SmileDental')
@section('page_title', 'Janji Temu')
@section('page_subtitle', 'Pantau jadwal kunjungan pasien dan status pelayanan.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Daftar Janji Temu</h3>
                <small class="text-secondary">{{ $appointments->count() }} jadwal tercatat</small>
            </div>
            <a href="/appointments/create" class="btn btn-primary"><i class="bi bi-calendar-plus-fill me-2"></i>Tambah Janji</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $a)
                        <tr>
                            <td class="fw-semibold">{{ $a->nama_pasien }}</td>
                            <td>{{ $a->nama_dokter }}</td>
                            <td>{{ $a->tanggal }}</td>
                            <td>{{ substr($a->jam, 0, 5) }}</td>
                            <td><span class="status-badge status-{{ $a->status }}">{{ $a->status }}</span></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="/appointments/edit/{{ $a->id }}"><i class="bi bi-pencil-square"></i></a>
                                <form class="d-inline" method="POST" action="/appointments/{{ $a->id }}" onsubmit="return confirm('Hapus janji temu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">Belum ada janji temu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

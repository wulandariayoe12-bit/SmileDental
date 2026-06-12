@extends('layouts.app')

@section('title', 'Jadwal Dokter - SmileDental')
@section('page_title', 'Jadwal Dokter')
@section('page_subtitle', 'Atur jam praktik dokter agar booking lebih terkontrol.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Daftar Jadwal</h3>
                <small class="text-secondary">{{ $schedules->count() }} jadwal tersedia</small>
            </div>
            <a href="/schedules/create" class="btn btn-primary"><i class="bi bi-clock-fill me-2"></i>Tambah Jadwal</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Dokter</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td class="fw-semibold">{{ $schedule->nama_dokter }}</td>
                            <td>{{ $schedule->hari }}</td>
                            <td>{{ substr($schedule->jam_mulai, 0, 5) }}</td>
                            <td>{{ substr($schedule->jam_selesai, 0, 5) }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="/schedules/edit/{{ $schedule->id }}"><i class="bi bi-pencil-square"></i></a>
                                <form class="d-inline" method="POST" action="/schedules/{{ $schedule->id }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">Belum ada jadwal dokter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

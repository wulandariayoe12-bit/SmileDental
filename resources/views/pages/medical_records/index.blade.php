@extends('layouts.app')

@section('title', 'Rekam Medis - SmileDental')
@section('page_title', 'Rekam Medis')
@section('page_subtitle', 'Dokumentasikan diagnosa, tindakan, dan catatan perawatan pasien.')

@section('content')
    <div class="sd-card">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Daftar Rekam Medis</h3>
                <small class="text-secondary">{{ $records->count() }} rekam medis tercatat</small>
            </div>
            <a href="/medical-records/create" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill me-2"></i>Tambah Rekam</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Diagnosa</th>
                        <th>Tindakan</th>
                        <th>Tanggal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="fw-semibold">{{ $record->nama_pasien }}</td>
                            <td>{{ $record->nama_dokter }}</td>
                            <td>{{ $record->diagnosa }}</td>
                            <td>{{ $record->tindakan ?: '-' }}</td>
                            <td>{{ $record->tanggal }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="/medical-records/edit/{{ $record->id }}"><i class="bi bi-pencil-square"></i></a>
                                <form class="d-inline" method="POST" action="/medical-records/{{ $record->id }}" onsubmit="return confirm('Hapus rekam medis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">Belum ada rekam medis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

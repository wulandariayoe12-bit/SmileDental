@extends('layouts.app')

@section('title', 'Profil Pasien - SmileDental')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Informasi data pasien dan riwayat rekam medis.')

@section('content')

<div class="row g-4">

    <div class="col-lg-4">
        <div class="sd-card">

            <div class="text-center">

                <div class="mb-3">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                        style="width:100px;height:100px;font-size:36px;font-weight:700;">
                        {{ strtoupper(substr($patient->nama,0,1)) }}
                    </div>
                </div>

                <h4 class="fw-bold mb-1">
                    {{ $patient->nama }}
                </h4>

                <p class="text-secondary">
                    Pasien SmileDental
                </p>

            </div>

            <hr>

            <table class="table table-borderless mb-0">
                <tr>
                    <td width="40%">
                        <strong>Tanggal Lahir</strong>
                    </td>
                    <td>
                        {{ $patient->tanggal_lahir ?: '-' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Jenis Kelamin</strong>
                    </td>
                    <td>
                        {{ $patient->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>No HP</strong>
                    </td>
                    <td>
                        {{ $patient->no_hp ?: '-' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Alamat</strong>
                    </td>
                    <td>
                        {{ $patient->alamat ?: '-' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Riwayat Penyakit</strong>
                    </td>
                    <td>
                        {{ $patient->riwayat_penyakit ?: '-' }}
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <div class="col-lg-8">
        <div class="sd-card">

            <div class="sd-card-header">
                <div>
                    <h3 class="h5 fw-bold mb-1">
                        Riwayat Rekam Medis
                    </h3>
                    <small class="text-secondary">
                        {{ $records->count() }} rekam medis ditemukan
                    </small>
                </div>
            </div>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Dokter</th>
                            <th>Diagnosa</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($records as $record)

                            <tr>
                                <td>{{ $record->tanggal }}</td>
                                <td>{{ $record->nama_dokter }}</td>
                                <td>{{ $record->diagnosa }}</td>
                                <td>{{ $record->tindakan ?: '-' }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="empty-state">
                                    Belum ada rekam medis.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection
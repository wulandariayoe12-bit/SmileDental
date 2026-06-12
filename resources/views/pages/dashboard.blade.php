@extends('layouts.app')

@section('title', 'Dashboard - SmileDental')
@section('page_title', 'Dashboard Klinik')
@section('page_subtitle', 'Pantau pasien, dokter, janji temu, dan pendapatan secara real-time.')

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="sd-card stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-label">Total Pasien</div>
                    <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                </div>
                <div>
                    <div class="stat-value" id="countPatients">{{ $jumlahPasien }}</div>
                    <small class="text-secondary">Profil pasien aktif</small>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="sd-card stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-label">Dokter</div>
                    <div class="stat-icon"><i class="bi bi-heart-pulse-fill"></i></div>
                </div>
                <div>
                    <div class="stat-value" id="countDoctors">{{ $jumlahDokter }}</div>
                    <small class="text-secondary">Tenaga medis tersedia</small>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="sd-card stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-label">Janji Temu</div>
                    <div class="stat-icon"><i class="bi bi-calendar2-week-fill"></i></div>
                </div>
                <div>
                    <div class="stat-value" id="countAppointments">{{ $jumlahJanjiTemu }}</div>
                    <small class="text-secondary"><span id="countToday">0</span> hari ini</small>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="sd-card stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-label">Pendapatan</div>
                    <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                </div>
                <div>
                    <div class="stat-value" id="countRevenue">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <small class="text-secondary"><span id="countPending">0</span> janji menunggu</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="sd-card h-100">
                <div class="sd-card-header">
                    <div>
                        <h3 class="h5 fw-bold mb-1">Aktivitas Bulanan</h3>
                        <small class="text-secondary">Jumlah janji temu per bulan.</small>
                    </div>
                    <span class="live-pill"><span class="pulse-dot"></span><span id="syncStatus">Live</span></span>
                </div>
                <div class="sd-card-body">
                    <div style="height: 310px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="sd-card h-100">
                <div class="sd-card-header">
                    <div>
                        <h3 class="h5 fw-bold mb-1">Aksi Cepat</h3>
                        <small class="text-secondary">Shortcut untuk operasional harian.</small>
                    </div>
                </div>
                <div class="sd-card-body d-grid gap-2">
                    <a class="btn btn-primary" href="/patients/create"><i class="bi bi-person-plus-fill me-2"></i>Tambah Pasien</a>
                    <a class="btn btn-outline-primary" href="/appointments/create"><i class="bi bi-calendar-plus-fill me-2"></i>Buat Janji Temu</a>
                    <a class="btn btn-outline-primary" href="/payments/create"><i class="bi bi-credit-card-fill me-2"></i>Catat Pembayaran</a>
                    <a class="btn btn-outline-secondary" href="/schedules/create"><i class="bi bi-clock-fill me-2"></i>Tambah Jadwal Dokter</a>
                </div>
            </div>
        </div>
    </div>

    <div class="sd-card mt-3">
        <div class="sd-card-header">
            <div>
                <h3 class="h5 fw-bold mb-1">Janji Temu Terbaru</h3>
                <small class="text-secondary">Data akan diperbarui otomatis tanpa reload halaman.</small>
            </div>
            <a class="btn btn-sm btn-primary" href="/appointments"><i class="bi bi-arrow-right-circle me-1"></i>Lihat Semua</a>
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
                    </tr>
                </thead>
                <tbody id="recentAppointments">
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td class="fw-semibold">{{ $appointment->nama_pasien }}</td>
                            <td>{{ $appointment->nama_dokter }}</td>
                            <td>{{ $appointment->tanggal }}</td>
                            <td>{{ substr($appointment->jam, 0, 5) }}</td>
                            <td><span class="status-badge status-{{ $appointment->status }}">{{ $appointment->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">Belum ada janji temu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const formatRupiah = value => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value || 0);

        const chartData = @json($chartData);
        const activityChart = new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: chartData.map(item => monthNames[item.bulan]),
                datasets: [{
                    label: 'Janji Temu',
                    data: chartData.map(item => item.total),
                    borderColor: '#0f9f9a',
                    backgroundColor: 'rgba(15, 159, 154, .14)',
                    borderWidth: 3,
                    fill: true,
                    tension: .35,
                    pointRadius: 4,
                    pointBackgroundColor: '#0f9f9a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        const setText = (id, value) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        };

        const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[character]));

        const renderAppointments = appointments => {
            const tbody = document.getElementById('recentAppointments');
            if (!tbody) {
                return;
            }

            if (!appointments.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="empty-state">Belum ada janji temu.</td></tr>';
                return;
            }

            tbody.innerHTML = appointments.map(appointment => `
                <tr>
                    <td class="fw-semibold">${escapeHtml(appointment.nama_pasien)}</td>
                    <td>${escapeHtml(appointment.nama_dokter)}</td>
                    <td>${escapeHtml(appointment.tanggal)}</td>
                    <td>${escapeHtml(String(appointment.jam).slice(0, 5))}</td>
                    <td><span class="status-badge status-${escapeHtml(appointment.status)}">${escapeHtml(appointment.status)}</span></td>
                </tr>
            `).join('');
        };

        const refreshDashboard = async () => {
            const syncStatus = document.getElementById('syncStatus');

            try {
                const response = await fetch('/dashboard/realtime');

                if (!response.ok) {
                    throw new Error('Gagal mengambil data');
                }

                const data = await response.json();

                setText('countPatients', data.counts.patients);
                setText('countDoctors', data.counts.doctors);
                setText('countAppointments', data.counts.appointments);
                setText('countRevenue', formatRupiah(data.counts.revenue));
                setText('countToday', data.counts.todayAppointments);
                setText('countPending', data.counts.pendingAppointments);

                activityChart.data.labels = data.chartData.map(item => monthNames[item.bulan]);
                activityChart.data.datasets[0].data = data.chartData.map(item => item.total);
                activityChart.update();

                renderAppointments(data.appointments);

                if (syncStatus) {
                    syncStatus.textContent = `Sinkron ${data.serverTime}`;
                }
            } catch (error) {
                if (syncStatus) {
                    syncStatus.textContent = 'Koneksi tertunda';
                }
            }
        };

        refreshDashboard();
        setInterval(refreshDashboard, 5000);
    </script>
@endpush

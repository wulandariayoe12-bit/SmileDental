<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SmileDental')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sd-ink: #182230;
            --sd-muted: #667085;
            --sd-line: #dbe3ea;
            --sd-bg: #f3f8fa;
            --sd-primary: #0f9f9a;
            --sd-primary-dark: #08726f;
            --sd-accent: #f5a524;
            --sd-danger: #d92d20;
            --sd-sidebar: #ffffff;
        }

        * {
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: var(--sd-bg);
            color: var(--sd-ink);
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        a {
            text-decoration: none;
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            background: var(--sd-sidebar);
            border-right: 1px solid var(--sd-line);
            display: flex;
            flex-direction: column;
            padding: 22px 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 8px 18px;
            border-bottom: 1px solid var(--sd-line);
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--sd-primary), #1db8d1);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 23px;
        }

        .brand-title {
            margin: 0;
            font-weight: 800;
            font-size: 1.13rem;
        }

        .brand-subtitle {
            color: var(--sd-muted);
            font-size: .78rem;
            margin-top: 2px;
        }

        .nav-menu {
            padding-top: 18px;
            display: grid;
            gap: 6px;
        }

        .nav-link {
            min-height: 44px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #475467;
            border-radius: 8px;
            font-weight: 650;
            padding: 10px 12px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--sd-primary-dark);
            background: #e7f7f5;
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid var(--sd-line);
            padding-top: 16px;
        }

        .content-area {
            min-width: 0;
            padding: 22px;
        }

        .topbar {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
            padding: 14px 18px;
            border: 1px solid var(--sd-line);
            border-radius: 8px;
            background: #fff;
        }

        .page-title {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .page-subtitle {
            margin: 3px 0 0;
            color: var(--sd-muted);
            font-size: .92rem;
        }

        .live-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 38px;
            padding: 8px 12px;
            border: 1px solid #b9ebe7;
            border-radius: 999px;
            color: var(--sd-primary-dark);
            background: #ecfbf9;
            font-size: .84rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #12b76a;
            box-shadow: 0 0 0 4px rgba(18, 183, 106, .16);
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 8px;
        }

        .sd-card {
            border: 1px solid var(--sd-line);
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(16, 24, 40, .05);
        }

        .sd-card-header {
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--sd-line);
        }

        .sd-card-body {
            padding: 18px;
        }

        .stat-card {
            min-height: 146px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: #e7f7f5;
            color: var(--sd-primary-dark);
            font-size: 1.25rem;
        }

        .stat-label {
            color: var(--sd-muted);
            font-size: .86rem;
            font-weight: 700;
        }

        .stat-value {
            margin: 8px 0 0;
            font-size: clamp(1.45rem, 2vw, 2rem);
            line-height: 1.1;
            font-weight: 800;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            color: #475467;
            background: #f8fafb;
            border-bottom: 1px solid var(--sd-line);
            font-size: .78rem;
            text-transform: uppercase;
        }

        .btn {
            border-radius: 8px;
            font-weight: 700;
        }

        .btn-primary {
            background: var(--sd-primary);
            border-color: var(--sd-primary);
        }

        .btn-primary:hover {
            background: var(--sd-primary-dark);
            border-color: var(--sd-primary-dark);
        }

        .form-control,
        .form-select {
            border-color: #ccd7df;
            border-radius: 8px;
            min-height: 44px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 800;
            text-transform: capitalize;
        }

        .status-pending,
        .status-belum {
            background: #fff6df;
            color: #98690c;
        }

        .status-selesai,
        .status-lunas {
            background: #e7f8ee;
            color: #067647;
        }

        .status-batal {
            background: #ffebe9;
            color: var(--sd-danger);
        }

        .empty-state {
            padding: 34px;
            text-align: center;
            color: var(--sd-muted);
        }

        @media (max-width: 991.98px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                height: auto;
                padding: 16px;
            }

            .nav-menu {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content-area {
                padding: 16px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 575.98px) {
            .nav-menu {
                grid-template-columns: 1fr;
            }

            .sd-card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .user-chip {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark"><i class="bi bi-hospital"></i></div>
                <div>
                    <h1 class="brand-title">SmileDental</h1>
                    <div class="brand-subtitle">Clinic Management</div>
                </div>
            </div>

            <nav class="nav-menu" aria-label="Navigasi utama">
                @php
                    $items = [
                        ['/dashboard', 'Dashboard', 'bi-grid-1x2-fill', 'dashboard*'],
                        ['/patients', 'Pasien', 'bi-people-fill', 'patients*'],
                        ['/doctors', 'Dokter', 'bi-heart-pulse-fill', 'doctors*'],
                        ['/services', 'Layanan', 'bi-clipboard2-pulse-fill', 'services*'],
                        ['/appointments', 'Janji Temu', 'bi-calendar2-week-fill', 'appointments*'],
                        ['/medical-records', 'Rekam Medis', 'bi-file-earmark-medical-fill', 'medical-records*'],
                        ['/payments', 'Pembayaran', 'bi-credit-card-2-front-fill', 'payments*'],
                        ['/schedules', 'Jadwal Dokter', 'bi-clock-history', 'schedules*'],
                    ];
                @endphp

                @foreach ($items as [$href, $label, $icon, $pattern])
                    <a class="nav-link {{ request()->is($pattern) ? 'active' : '' }}" href="{{ $href }}">
                        <i class="bi {{ $icon }}"></i>
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="/logout">
                    @csrf
                    <button class="nav-link text-danger w-100 bg-transparent border-0 text-start" type="submit">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="content-area">
            <header class="topbar">
                <div>
                    <h2 class="page-title">@yield('page_title', 'SmileDental')</h2>
                    <p class="page-subtitle">@yield('page_subtitle', 'Operasional klinik gigi dalam satu dashboard.')</p>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="live-pill">
                        <span class="pulse-dot"></span>
                        <span id="liveClock">{{ now()->format('d M Y H:i:s') }}</span>
                    </div>

                    <div class="user-chip">
                        <img class="avatar" alt="Admin" src="https://ui-avatars.com/api/?name={{ urlencode(session('user_name', 'SmileDental Admin')) }}&background=0f9f9a&color=fff">
                        <div>
                            <div class="fw-bold">{{ session('user_name', 'SmileDental Admin') }}</div>
                            <small class="text-secondary">{{ ucfirst(session('role', 'admin')) }}</small>
                        </div>
                    </div>
                </div>
            </header>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const liveClock = document.getElementById('liveClock');

        if (liveClock) {
            setInterval(() => {
                liveClock.textContent = new Intl.DateTimeFormat('id-ID', {
                    dateStyle: 'medium',
                    timeStyle: 'medium'
                }).format(new Date());
            }, 1000);
        }
    </script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Download aplikasi SmileDental untuk booking janji temu, melihat jadwal dokter, rekam kunjungan, dan pembayaran klinik gigi dari ponsel.">
    <title>Download SmileDental App</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ink: #182230;
            --muted: #667085;
            --line: #dbe3ea;
            --surface: #ffffff;
            --soft: #f4faf9;
            --primary: #0f9f9a;
            --primary-dark: #08726f;
            --accent: #f5a524;
            --navy: #203247;
        }

        * {
            box-sizing: border-box;
            letter-spacing: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: #ffffff;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .site-header {
            position: absolute;
            z-index: 10;
            top: 0;
            left: 0;
            right: 0;
            padding: 18px clamp(18px, 4vw, 54px);
        }

        .nav-bar {
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 8px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--navy);
            font-weight: 900;
            font-size: 1.1rem;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            box-shadow: 0 10px 22px rgba(15, 159, 154, .26);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 14px;
            border-radius: 8px;
            color: #334155;
            font-weight: 800;
            font-size: .92rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .72);
        }

        .nav-link.primary {
            color: #fff;
            background: var(--primary-dark);
        }

        .hero {
            position: relative;
            min-height: min(760px, 82svh);
            display: flex;
            align-items: center;
            padding: 120px clamp(22px, 5vw, 72px) 64px;
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(90deg, rgba(255, 255, 255, .96) 0%, rgba(255, 255, 255, .86) 34%, rgba(255, 255, 255, .34) 61%, rgba(255, 255, 255, .08) 100%),
                url("{{ asset('images/smiledental-app-hero.png') }}") center / cover no-repeat;
        }

        .hero-content {
            width: min(670px, 100%);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            color: var(--primary-dark);
            font-size: .86rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 0;
            color: #12202f;
            font-size: clamp(2.6rem, 6vw, 5.8rem);
            line-height: .96;
            font-weight: 900;
        }

        .hero-copy {
            max-width: 600px;
            margin: 22px 0 0;
            color: #435367;
            font-size: clamp(1rem, 1.65vw, 1.28rem);
            line-height: 1.65;
            font-weight: 650;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin-top: 30px;
        }

        .button {
            min-height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-weight: 900;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .button.download {
            color: #fff;
            background: var(--primary);
            box-shadow: 0 18px 36px rgba(15, 159, 154, .26);
        }

        .button.download:hover {
            background: var(--primary-dark);
        }

        .button.secondary {
            color: var(--navy);
            border-color: rgba(32, 50, 71, .18);
            background: rgba(255, 255, 255, .78);
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            min-height: 38px;
            padding: 8px 12px;
            border: 1px solid rgba(15, 159, 154, .18);
            border-radius: 8px;
            color: #234154;
            background: rgba(255, 255, 255, .72);
            font-size: .9rem;
            font-weight: 800;
        }

        .download-panel {
            padding: 36px clamp(22px, 5vw, 72px);
            background: var(--navy);
            color: #fff;
        }

        .download-inner {
            width: min(1180px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 22px;
        }

        .download-inner h2 {
            margin: 0;
            font-size: clamp(1.45rem, 2.4vw, 2.1rem);
            line-height: 1.18;
            font-weight: 900;
        }

        .download-inner p {
            max-width: 720px;
            margin: 8px 0 0;
            color: rgba(255, 255, 255, .74);
            line-height: 1.6;
            font-weight: 600;
        }

        .alert {
            margin-top: 16px;
            padding: 12px 14px;
            border: 1px solid rgba(245, 165, 36, .42);
            border-radius: 8px;
            color: #fff5db;
            background: rgba(245, 165, 36, .12);
            font-weight: 750;
        }

        .section {
            padding: 76px clamp(22px, 5vw, 72px);
        }

        .section-inner {
            width: min(1180px, 100%);
            margin: 0 auto;
        }

        .section-heading {
            max-width: 760px;
            margin-bottom: 34px;
        }

        .section-heading h2 {
            margin: 0;
            color: #162233;
            font-size: clamp(2rem, 4vw, 3.4rem);
            line-height: 1.04;
            font-weight: 900;
        }

        .section-heading p {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 1.04rem;
            line-height: 1.7;
            font-weight: 600;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .feature-card {
            min-height: 214px;
            padding: 22px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: 0 14px 32px rgba(16, 24, 40, .05);
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            color: var(--primary-dark);
            background: #e8f8f6;
            font-size: 1.25rem;
        }

        .feature-card h3 {
            margin: 18px 0 8px;
            font-size: 1.08rem;
            line-height: 1.25;
            font-weight: 900;
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.58;
            font-weight: 550;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .step {
            min-height: 176px;
            padding: 22px;
            border: 1px solid #cce9e7;
            border-radius: 8px;
            background: var(--soft);
        }

        .step-number {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            color: #fff;
            background: var(--primary);
            font-weight: 900;
        }

        .step h3 {
            margin: 18px 0 8px;
            font-size: 1.05rem;
            font-weight: 900;
        }

        .step p {
            margin: 0;
            color: #4c6474;
            line-height: 1.58;
            font-weight: 600;
        }

        .site-footer {
            padding: 28px clamp(22px, 5vw, 72px);
            border-top: 1px solid var(--line);
            color: var(--muted);
            background: #fff;
            font-size: .92rem;
            font-weight: 650;
        }

        .footer-inner {
            width: min(1180px, 100%);
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
        }

        @media (max-width: 940px) {
            .hero {
                min-height: 76svh;
                padding-top: 104px;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, .96) 0%, rgba(255, 255, 255, .86) 58%, rgba(255, 255, 255, .24) 100%),
                    url("{{ asset('images/smiledental-app-hero.png') }}") 66% center / cover no-repeat;
            }

            .download-inner,
            .feature-grid,
            .steps {
                grid-template-columns: 1fr;
            }

            .download-inner .button {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .site-header {
                padding: 14px 14px 0;
            }

            .nav-bar {
                padding: 0;
            }

            .brand span {
                display: none;
            }

            .nav-link:not(.primary) {
                display: none;
            }

            .hero {
                min-height: 80svh;
                padding: 96px 18px 42px;
                align-items: flex-start;
            }

            .hero h1 {
                font-size: clamp(2.55rem, 13vw, 3.7rem);
            }

            .button {
                width: 100%;
            }

            .hero-meta {
                display: none;
            }

            .section {
                padding: 56px 18px;
            }

            .feature-card,
            .step {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    @php
        $appAvailable = file_exists(public_path('downloads/smiledental.apk'));
    @endphp

    <header class="site-header">
        <nav class="nav-bar" aria-label="Navigasi landing page">
            <a class="brand" href="/">
                <span class="brand-mark"><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i></span>
                <span>SmileDental</span>
            </a>

            <div class="nav-links">
                <a class="nav-link" href="#fitur">Fitur</a>
                <a class="nav-link" href="#cara-download">Cara Download</a>
                <a class="nav-link primary" href="/app">
                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                    <span>Masuk Web</span>
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero" aria-label="Download aplikasi SmileDental">
            <div class="hero-content">
                <div class="eyebrow">
                    <i class="bi bi-phone-fill" aria-hidden="true"></i>
                    Aplikasi pasien SmileDental
                </div>
                <h1>Download SmileDental</h1>
                <p class="hero-copy">
                    Booking janji temu, cek jadwal dokter, lihat riwayat perawatan, dan pantau pembayaran klinik gigi langsung dari ponsel.
                </p>

                <div class="hero-actions">
                    <a class="button download" href="{{ route('download.app') }}">
                        <i class="bi bi-download" aria-hidden="true"></i>
                        Download APK
                    </a>
                    <a class="button secondary" href="#fitur">
                        <i class="bi bi-stars" aria-hidden="true"></i>
                        Lihat Fitur
                    </a>
                </div>

                <div class="hero-meta" aria-label="Keunggulan aplikasi">
                    <span class="meta-item"><i class="bi bi-calendar2-check" aria-hidden="true"></i> Booking cepat</span>
                    <span class="meta-item"><i class="bi bi-shield-check" aria-hidden="true"></i> Data klinik rapi</span>
                    <span class="meta-item"><i class="bi bi-credit-card-2-front" aria-hidden="true"></i> Pantau pembayaran</span>
                </div>
            </div>
        </section>

        <section class="download-panel" id="download" aria-label="Area download aplikasi">
            <div class="download-inner">
                <div>
                    <h2>{{ $appAvailable ? 'File aplikasi siap diunduh.' : 'Siapkan file aplikasi untuk mengaktifkan download.' }}</h2>
                    <p>
                        {{ $appAvailable
                            ? 'Klik tombol download untuk mendapatkan installer SmileDental versi Android.'
                            : 'Letakkan file APK dengan nama smiledental.apk di folder public/downloads. Setelah itu tombol Download APK akan langsung mengunduh file tersebut.' }}
                    </p>

                    @if (session('download_error'))
                        <div class="alert">{{ session('download_error') }}</div>
                    @endif
                </div>

                <a class="button download" href="{{ route('download.app') }}">
                    <i class="bi bi-android2" aria-hidden="true"></i>
                    Download APK
                </a>
            </div>
        </section>

        <section class="section" id="fitur">
            <div class="section-inner">
                <div class="section-heading">
                    <h2>Satu aplikasi untuk pengalaman pasien yang lebih praktis.</h2>
                    <p>SmileDental membantu pasien terhubung dengan layanan klinik tanpa bolak-balik membuka dashboard web.</p>
                </div>

                <div class="feature-grid">
                    <article class="feature-card">
                        <div class="feature-icon"><i class="bi bi-calendar-heart" aria-hidden="true"></i></div>
                        <h3>Booking Janji Temu</h3>
                        <p>Pilih jadwal dokter dan layanan yang dibutuhkan dengan alur yang ringkas.</p>
                    </article>

                    <article class="feature-card">
                        <div class="feature-icon"><i class="bi bi-person-vcard" aria-hidden="true"></i></div>
                        <h3>Profil Dokter</h3>
                        <p>Lihat informasi dokter, layanan, dan jadwal praktik sebelum membuat reservasi.</p>
                    </article>

                    <article class="feature-card">
                        <div class="feature-icon"><i class="bi bi-file-medical" aria-hidden="true"></i></div>
                        <h3>Riwayat Perawatan</h3>
                        <p>Catatan kunjungan tersusun rapi sehingga pasien mudah mengikuti progres perawatan.</p>
                    </article>

                    <article class="feature-card">
                        <div class="feature-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></div>
                        <h3>Status Pembayaran</h3>
                        <p>Pantau tagihan, status lunas, dan informasi pembayaran dari aplikasi mobile.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="cara-download">
            <div class="section-inner">
                <div class="section-heading">
                    <h2>Cara mengaktifkan halaman download.</h2>
                    <p>Landing page ini sudah siap. Tinggal pasang file aplikasinya saat APK final tersedia.</p>
                </div>

                <div class="steps">
                    <article class="step">
                        <div class="step-number">1</div>
                        <h3>Build APK aplikasi mobile</h3>
                        <p>Ekspor aplikasi Android SmileDental menjadi file APK final.</p>
                    </article>

                    <article class="step">
                        <div class="step-number">2</div>
                        <h3>Simpan di folder download</h3>
                        <p>Letakkan file tersebut sebagai public/downloads/smiledental.apk.</p>
                    </article>

                    <article class="step">
                        <div class="step-number">3</div>
                        <h3>Bagikan landing page</h3>
                        <p>Pengunjung membuka halaman utama lalu menekan tombol Download APK.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <span>SmileDental Clinic Management</span>
            <span>Landing page download aplikasi pasien.</span>
        </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SmileDental</title>
    <style>
        :root {
            --ink: #182230;
            --muted: #667085;
            --line: #dbe3ea;
            --primary: #0f9f9a;
            --primary-dark: #08726f;
            --surface: #ffffff;
        }

        * {
            box-sizing: border-box;
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at 18% 16%, rgba(255, 255, 255, .18), transparent 26%),
                radial-gradient(circle at 76% 76%, rgba(255, 255, 255, .16), transparent 28%),
                linear-gradient(140deg, rgba(15, 159, 154, .94), rgba(29, 184, 209, .78)),
                #0f9f9a;
            color: var(--ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .16;
            background:
                linear-gradient(125deg, transparent 0 22%, rgba(255, 255, 255, .28) 22% 23%, transparent 23% 100%),
                linear-gradient(35deg, transparent 0 55%, rgba(255, 255, 255, .18) 55% 57%, transparent 57% 100%);
        }

        a {
            color: #08726f;
            font-weight: 800;
        }

        .auth-shell {
            position: relative;
            width: min(980px, 100%);
            min-height: 520px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 420px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .48);
            border-radius: 8px;
            background: rgba(255, 255, 255, .96);
            box-shadow: 0 24px 70px rgba(16, 24, 40, .24);
        }

        .auth-panel {
            min-width: 0;
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 44px;
            color: #fff;
            background:
                radial-gradient(circle at 18% 12%, rgba(255, 255, 255, .12), transparent 30%),
                linear-gradient(145deg, #08726f, #0f9f9a);
        }

        .brand-mark {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, .16);
            font-size: 25px;
        }

        .auth-panel h1 {
            max-width: 620px;
            margin: 28px 0 0;
            font-size: clamp(2.1rem, 4vw, 3.15rem);
            line-height: 1.05;
            font-weight: 850;
        }

        .auth-panel p {
            max-width: 570px;
            margin: 18px 0 0;
            color: rgba(255, 255, 255, .78);
            font-size: 1.24rem;
            line-height: 1.5;
            font-weight: 650;
        }

        .badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 7px 16px;
            border-radius: 8px;
            background: #fff;
            color: #08726f;
            font-size: .82rem;
            font-weight: 800;
        }

        .auth-card {
            padding: 44px;
            background: var(--surface);
        }

        .auth-card h2 {
            margin: 0;
            font-size: 2rem;
            line-height: 1.15;
            font-weight: 850;
        }

        .auth-card > p {
            margin: 8px 0 28px;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.45;
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border: 1px solid #fecdca;
            border-radius: 8px;
            color: #b42318;
            background: #fffbfa;
            font-weight: 650;
        }

        .form-grid {
            display: grid;
            gap: 18px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        label {
            font-weight: 800;
            font-size: .94rem;
        }

        input {
            width: 100%;
            min-height: 48px;
            padding: 10px 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            color: var(--ink);
            font: inherit;
            outline: none;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 159, 154, .12);
        }

        .login-button {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 850;
        }

        .login-button:hover {
            background: var(--primary-dark);
        }

        .footer-text {
            margin: 22px 0 0;
            color: var(--muted);
        }

        @media (max-width: 820px) {
            body {
                padding: 16px;
            }

            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-panel,
            .auth-card {
                padding: 30px;
            }

            .auth-panel h1 {
                font-size: 2.15rem;
            }
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <section class="auth-panel">
            <div>
                <div class="brand-mark" aria-hidden="true">▦</div>
                <h1>SmileDental Clinic Management</h1>
                <p>Kelola pasien, dokter, jadwal, rekam medis, dan pembayaran dalam satu sistem klinik yang rapi.</p>
            </div>
            <div class="badge-row">
                <span class="badge">Real-time dashboard</span>
                <span class="badge">Admin ready</span>
            </div>
        </section>

        <section class="auth-card">
            <h2>Masuk Admin</h2>
            <p>Gunakan akun klinik untuk membuka dashboard.</p>

            @if(session('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert" style="border-color: #abefc6; color: #067647; background: #f6fef9;">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert">Periksa kembali email dan password.</div>
            @endif

            <form method="POST" action="/login" class="form-grid">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="admin@klinik.com" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button class="login-button">Login</button>
            </form>

            <p class="footer-text">
                Belum punya akun? <a href="/register">Daftar sekarang</a>
            </p>
        </section>
    </main>
</body>
</html>

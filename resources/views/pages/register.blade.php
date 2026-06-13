<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - SmileDental</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background: #f3f8fa;
            font-family: Inter, system-ui, sans-serif;
            color: #182230;
        }

        .auth-card {
            width: min(520px, calc(100% - 32px));
            padding: 34px;
            border: 1px solid #dbe3ea;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 18px 46px rgba(16, 24, 40, .08);
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            color: #fff;
            background: #0f9f9a;
            font-size: 23px;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border-radius: 8px;
        }

        .btn-primary {
            min-height: 46px;
            border-radius: 8px;
            border-color: #0f9f9a;
            background: #0f9f9a;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <main class="auth-card">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-mark"><i class="bi bi-hospital"></i></div>
            <div>
                <h1 class="h3 fw-bold mb-0">Buat Akun</h1>
                <p class="text-secondary mb-0">Daftar pengguna SmileDental.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/register" method="POST" class="d-grid gap-3">
            @csrf

            <div>
                <label class="form-label fw-semibold">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div>
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div>
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" minlength="8" required>
            </div>

            <button class="btn btn-primary">
                <i class="bi bi-person-check-fill me-2"></i>Register
            </button>
        </form>

        <p class="mt-4 mb-0 text-secondary">
            Sudah punya akun? <a class="fw-bold text-success" href="/login">Login</a>
        </p>
    </main>
</body>
</html>

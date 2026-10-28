<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Info Loker UMKM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* Cegah blob dekoratif memicu scroll horizontal di layar kecil */
        html { overflow-x: hidden; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #16213e;
            background-image: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 40%, #16213e 100%);
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative blobs */
        body::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(236,72,153,0.15) 0%, transparent 70%);
            top: -100px; left: -100px;
            border-radius: 50%;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
            bottom: -80px; right: -80px;
            border-radius: 50%;
        }

        .card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            box-shadow: 0 40px 80px rgba(0,0,0,0.5);
        }

        .logo-area {
            text-align: center;
            margin-bottom: 2rem;
        }

        .icon-badge {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #db2777, #7c3aed);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 12px 32px rgba(236,72,153,0.4);
        }

        .icon-badge i { color: white; font-size: 1.6rem; }

        .title {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.25rem;
        }

        .subtitle {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.62);
            font-weight: 500;
        }

        .badge-admin {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(236,72,153,0.15);
            border: 1px solid rgba(236,72,153,0.3);
            color: #f9a8d4;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            margin-top: 0.6rem;
            letter-spacing: 0.05em;
        }

        /* Alert error */
        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 1.2rem;
        }
        .alert-error p {
            color: #fca5a5;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Alert success */
        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.3);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 1.2rem;
        }
        .alert-success p {
            color: #86efac;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: rgba(255,255,255,0.72);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 0.5rem;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 0.9rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            outline: none;
            transition: all 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: rgba(236,72,153,0.6);
            background: rgba(236,72,153,0.06);
            box-shadow: 0 0 0 4px rgba(236,72,153,0.1);
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255,255,255,0.2);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #ec4899;
            cursor: pointer;
        }

        .remember-row span {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.68);
            font-weight: 500;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #db2777, #7c3aed);
            border: none;
            border-radius: 12px;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 8px 24px rgba(236,72,153,0.35);
            transition: all 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(236,72,153,0.5);
        }

        .btn-login:active { transform: translateY(0); }

        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 1.5rem 0;
        }

        .back-link {
            text-align: center;
        }

        .back-link a {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.62);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .back-link a:hover { color: rgba(255,255,255,0.6); }

        @media (max-width: 420px) {
            .card { padding: 1.75rem 1.5rem; }
            .btn-login { padding: 0.85rem; font-size: 0.85rem; }
            .title { font-size: 1.35rem; }
        }

        .security-note {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Logo & Title -->
        <div class="logo-area">
            <div class="icon-badge">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h1 class="title">Admin Panel</h1>
            <p class="subtitle">Info Loker UMKM — Sistem Moderasi</p>
            <span class="badge-admin">
                <i class="fa-solid fa-lock"></i> AKSES TERBATAS
            </span>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert-success">
                <p><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</p>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            <div>
                <label for="email">Email Admin</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="admin@loker.com"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div>
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <span>Ingat sesi saya</span>
            </div>

            <button type="submit" class="btn-login" id="btn-admin-login">
                <i class="fa-solid fa-right-to-bracket"></i>
                Masuk ke Dashboard Admin
            </button>
        </form>

        <hr class="divider">

        <div class="back-link">
            <a href="{{ route('home') }}">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <p class="security-note">
        <i class="fa-solid fa-shield-halved"></i>
        Halaman ini dienkripsi dan dilindungi. Akses tidak sah akan dicatat.
    </p>
</body>
</html>

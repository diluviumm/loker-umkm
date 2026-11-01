<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin - Info Loker UMKM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { overflow-x: hidden; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #16213e;
            background-image: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 40%, #16213e 100%);
            background-attachment: fixed;
            padding: 1.5rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Dekorasi lembut; tidak menangkap klik dan tidak memicu scroll */
        body::before {
            content: '';
            position: fixed;
            width: min(500px, 80vw); height: min(500px, 80vw);
            background: radial-gradient(circle, rgba(236,72,153,0.15) 0%, transparent 70%);
            top: -140px; left: -140px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            width: min(420px, 70vw); height: min(420px, 70vw);
            background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
            bottom: -120px; right: -120px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* Kolom: kartu + catatan keamanan bertumpuk, bukan berdampingan */
        .shell {
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 10;
        }

        .card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            padding: 2.25rem;
            width: 100%;
            box-shadow: 0 40px 80px rgba(0,0,0,0.5);
        }

        .logo-area {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .icon-badge {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #db2777, #7c3aed);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.9rem;
            box-shadow: 0 12px 32px rgba(236,72,153,0.35);
        }

        .icon-badge i { color: #fff; font-size: 1.5rem; }

        .title {
            font-size: 1.45rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.2rem;
            letter-spacing: -0.01em;
        }

        .subtitle {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.66);
            font-weight: 500;
        }

        .badge-admin {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(236,72,153,0.15);
            border: 1px solid rgba(236,72,153,0.32);
            color: #f9a8d4;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            margin-top: 0.65rem;
            letter-spacing: 0.05em;
        }

        .alert {
            border-radius: 12px;
            padding: 0.8rem 0.95rem;
            margin-bottom: 1.1rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .alert i { margin-top: 0.12rem; }
        .alert-error { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.35); color: #fca5a5; }
        .alert-success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.35); color: #86efac; }

        label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255,255,255,0.78);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 0.45rem;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 1.1rem;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.45);
            font-size: 0.9rem;
            pointer-events: none;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            background: rgba(255,255,255,0.09);
            border: 1.5px solid rgba(255,255,255,0.28);
            border-radius: 12px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.92rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        input[type="email"]:hover,
        input[type="password"]:hover {
            border-color: rgba(255,255,255,0.3);
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: rgba(236,72,153,0.75);
            background: rgba(236,72,153,0.1);
            box-shadow: 0 0 0 4px rgba(236,72,153,0.16);
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255,255,255,0.58);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 1.4rem;
        }

        .remember-row input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px; height: 18px;
            border-radius: 5px;
            border: 1.5px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.08);
            cursor: pointer;
            display: grid;
            place-content: center;
            transition: background 0.15s, border-color 0.15s;
            flex: 0 0 auto;
        }

        .remember-row input[type="checkbox"]::after {
            content: '';
            width: 9px; height: 5px;
            border-left: 2px solid #fff;
            border-bottom: 2px solid #fff;
            transform: rotate(-45deg) translateY(-1px);
            opacity: 0;
            transition: opacity 0.12s;
        }

        .remember-row input[type="checkbox"]:checked {
            background: #db2777;
            border-color: #db2777;
        }

        .remember-row input[type="checkbox"]:checked::after { opacity: 1; }

        .remember-row input[type="checkbox"]:focus-visible {
            outline: 2px solid #f9a8d4;
            outline-offset: 2px;
        }

        .remember-row label {
            margin: 0;
            text-transform: none;
            letter-spacing: 0;
            font-size: 0.82rem;
            font-weight: 500;
            color: rgba(255,255,255,0.74);
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 0.95rem;
            background: linear-gradient(135deg, #db2777, #7c3aed);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.94rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 8px 24px rgba(236,72,153,0.32);
            transition: transform 0.18s, box-shadow 0.18s, filter 0.18s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(236,72,153,0.45);
            filter: brightness(1.05);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login:focus-visible {
            outline: 2px solid #f9a8d4;
            outline-offset: 3px;
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 1.4rem 0;
        }

        .back-link { text-align: center; }

        .back-link a {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.68);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .back-link a:hover { color: #fff; }

        .security-note {
            text-align: center;
            font-size: 0.72rem;
            line-height: 1.5;
            color: rgba(255,255,255,0.62);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0 0.5rem;
        }

        @media (max-width: 420px) {
            .card { padding: 1.6rem 1.25rem; border-radius: 20px; }
            .btn-login { padding: 0.85rem; font-size: 0.85rem; }
            .title { font-size: 1.3rem; }
            .icon-badge { width: 54px; height: 54px; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
            .btn-login:hover { transform: none; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="card">
            <!-- Logo & Title -->
            <div class="logo-area">
                <div class="icon-badge">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h1 class="title">Admin Panel</h1>
                <p class="subtitle">Info Loker UMKM Kaltim — Sistem Moderasi</p>
                <span class="badge-admin">
                    <i class="fa-solid fa-lock"></i> AKSES TERBATAS
                </span>
            </div>

            @if($errors->any())
                <div class="alert alert-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success" role="status">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                <div>
                    <label for="email">Email Admin</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-envelope input-icon"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               placeholder="admin@loker.com" required autocomplete="username" autofocus>
                    </div>
                </div>

                <div>
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password" id="password"
                               placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat sesi saya</label>
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
            <span>Halaman ini dienkripsi dan dilindungi. Akses tidak sah akan dicatat.</span>
        </p>
    </div>
</body>
</html>

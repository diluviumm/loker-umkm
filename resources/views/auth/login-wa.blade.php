<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk via WhatsApp - Info Loker UMKM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .green-gradient { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%); }
        .card-shadow { box-shadow: 0 25px 60px -10px rgba(16,185,129,0.25); }
    </style>
</head>
<body class="min-h-full font-sans antialiased green-gradient flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 mb-6 group">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/40">
                <i class="fa-brands fa-whatsapp text-white text-xl"></i>
            </div>
            <span class="text-2xl font-extrabold text-slate-900">Loker<span class="text-emerald-600">UMKM</span></span>
        </a>

        <div class="bg-white rounded-2xl p-6 card-shadow border border-emerald-100">
            <div class="text-center mb-6">
                <h1 class="text-xl font-extrabold text-slate-900">Masuk via WhatsApp</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Kami kirim kode OTP 6 angka ke WhatsApp Anda</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-rose-700 font-semibold text-xs flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <p class="text-emerald-700 font-semibold text-xs flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </p>
                </div>
            @endif

            @if(session()->has('otp_phone'))
                <form action="{{ route('login.wa.verify') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="code" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kode OTP</label>
                        <input type="text" name="code" id="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus
                            placeholder="123456"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100 text-center tracking-[0.4em] font-extrabold text-lg text-slate-800">
                    </div>

                    <p class="text-xs text-slate-500 font-medium">
                        Kode dikirim ke <strong>{{ session('otp_phone') }}</strong>. Berlaku 5 menit.
                    </p>

                    <button type="submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-md shadow-emerald-700/30 transition-all text-sm">
                        <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Verifikasi &amp; Masuk
                    </button>
                </form>

                <form action="{{ route('login.wa.send') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="phone" value="{{ session('otp_phone') }}">
                    <button type="submit" class="w-full py-2 text-xs font-bold text-emerald-700 hover:text-emerald-900">
                        <i class="fa-solid fa-rotate-right mr-1"></i> Kirim ulang kode
                    </button>
                </form>
            @else
                <form action="{{ route('login.wa.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nomor WhatsApp</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                            </span>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                placeholder="08xxxxxxxxxx"
                                class="w-full pl-10 pr-3 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100 font-semibold text-sm text-slate-800">
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium mt-1">Gunakan nomor yang terdaftar di akun Anda.</p>
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-md shadow-emerald-700/30 transition-all text-sm">
                        <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim Kode OTP
                    </button>
                </form>
            @endif

            <div class="mt-5 pt-4 border-t border-slate-100 text-center space-y-2">
                <a href="{{ route('login') }}" class="block text-xs font-semibold text-slate-500 hover:text-emerald-600">
                    <i class="fa-solid fa-envelope mr-1"></i> Masuk dengan email &amp; password
                </a>
                <a href="{{ route('register') }}" class="block text-xs font-semibold text-slate-500 hover:text-emerald-600">
                    <i class="fa-solid fa-user-plus mr-1"></i> Belum punya akun? Daftar
                </a>
                <a href="{{ route('home') }}" class="block text-xs font-semibold text-slate-500 hover:text-slate-700">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke beranda
                </a>
            </div>
        </div>

        <p class="mt-4 text-center text-[11px] font-medium text-slate-600">
            OTP dikirim lewat gateway Fonnte. Tanpa konfigurasi Fonnte, kode hanya muncul di log server (mode demo).
        </p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Info Loker UMKM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .pink-gradient { background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 50%, #fecdd3 100%); }
        .card-shadow { box-shadow: 0 25px 60px -10px rgba(244,63,94,0.2); }
    </style>
</head>
<body class="min-h-full font-sans antialiased pink-gradient flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 mb-8 group">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-pink-500 to-rose-500 flex items-center justify-center shadow-lg shadow-pink-500/40 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-briefcase text-white text-xl"></i>
            </div>
            <span class="text-2xl font-extrabold text-slate-900">Loker<span class="text-pink-500">UMKM</span></span>
        </a>

        <!-- Card -->
        <div class="bg-white rounded-2xl p-6 card-shadow border border-pink-100">
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-pink-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-right-to-bracket text-pink-500 text-2xl"></i>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900">Masuk ke Akun</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Pemilik usaha &amp; pelamar</p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                    @foreach($errors->all() as $error)
                        <p class="text-rose-700 font-bold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                    <p class="text-emerald-700 font-bold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </p>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-pink-400"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="email@usahaanda.com"
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 text-slate-800 font-semibold transition-all text-sm"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-pink-400"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            placeholder="••••••••"
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 text-slate-800 font-semibold transition-all text-sm"
                        >
                    </div>
                </div>

                <!-- Remember me -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 accent-pink-500">
                    <label for="remember" class="text-sm font-semibold text-slate-600">Ingat saya</label>
                </div>

                <!-- Submit -->
                <button type="submit" id="btn-login" class="w-full py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-bold rounded-xl hover:from-pink-700 hover:to-rose-700 shadow-lg shadow-pink-500/35 hover:shadow-pink-500/50 transition-all transform hover:-translate-y-0.5 active:translate-y-0 text-sm">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk Sekarang
                </button>
            </form>


            <div class="mt-4">
                <a href="{{ route('login.wa') }}" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-md shadow-emerald-700/25 transition-all text-sm flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i> Masuk dengan WhatsApp (OTP)
                </a>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm font-semibold text-slate-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-pink-600 font-black hover:underline">Daftar akun baru</a>
                </p>
            </div>

            <div class="mt-4 pt-4 border-t border-pink-100 text-center">
                <a href="{{ route('home') }}" class="text-sm font-bold text-slate-500 hover:text-pink-500 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Note untuk pencari kerja -->
        <div class="mt-5 text-center p-4 bg-white/70 rounded-2xl border border-pink-100">
            <p class="text-xs font-bold text-slate-500">
                <i class="fa-solid fa-info-circle text-pink-400 mr-1"></i>
                Pelamar bisa langsung cari loker tanpa akun. <a href="{{ route('home') }}" class="text-pink-600 font-black">Cari loker di beranda →</a>
            </p>
        </div>
    </div>
</body>
</html>
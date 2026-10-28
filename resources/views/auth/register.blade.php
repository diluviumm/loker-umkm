<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemilik Usaha - Info Loker UMKM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['"Outfit"', 'sans-serif'] } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .pink-gradient { background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 50%, #fecdd3 100%); }
        .card-shadow { box-shadow: 0 25px 60px -10px rgba(244,63,94,0.2); }
    </style>
</head>
<body class="min-h-full font-sans antialiased pink-gradient flex items-center justify-center p-4 py-10">

    <div class="w-full max-w-md">
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
                    <i class="fa-solid fa-store text-pink-500 text-2xl"></i>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900">Daftar sebagai Pemilik Usaha</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Buat akun gratis dan mulai pasang loker UMKM Anda</p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                    @foreach($errors->all() as $error)
                        <p class="text-rose-700 font-bold text-sm flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <!-- Keuntungan daftar -->
            <div class="grid grid-cols-3 gap-2 mb-5">
                <div class="text-center p-3 bg-pink-50 rounded-2xl border border-pink-100">
                    <i class="fa-solid fa-rotate text-pink-500 text-lg mb-1"></i>
                    <p class="text-xs font-black text-slate-700">Data Tersimpan</p>
                    <p class="text-xs text-slate-600 font-semibold">Tidak perlu isi ulang</p>
                </div>
                <div class="text-center p-3 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <i class="fa-solid fa-chart-line text-emerald-500 text-lg mb-1"></i>
                    <p class="text-xs font-black text-slate-700">Pantau Loker</p>
                    <p class="text-xs text-slate-600 font-semibold">Status real-time</p>
                </div>
                <div class="text-center p-3 bg-blue-50 rounded-2xl border border-blue-100">
                    <i class="fa-solid fa-infinity text-blue-500 text-lg mb-1"></i>
                    <p class="text-xs font-black text-slate-700">Multi Loker</p>
                    <p class="text-xs text-slate-600 font-semibold">Posting berkali-kali</p>
                </div>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Nama Lengkap Anda <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-pink-400"></i>
                        </span>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Nama lengkap pemilik usaha"
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 text-slate-800 font-semibold transition-all text-sm"
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Email <span class="text-rose-500">*</span>
                    </label>
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
                    <p class="text-xs text-slate-500 font-semibold mt-1.5">Email ini akan digunakan untuk login</p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-pink-400"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            placeholder="Minimal 6 karakter"
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 text-slate-800 font-semibold transition-all text-sm"
                        >
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Konfirmasi Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock-open text-pink-400"></i>
                        </span>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            required
                            placeholder="Ulangi password"
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 text-slate-800 font-semibold transition-all text-sm"
                        >
                    </div>
                </div>

                <!-- Note -->
                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                    <p class="text-xs font-bold text-blue-700 flex items-start gap-2">
                        <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                        Setelah daftar, Anda akan diminta melengkapi data usaha (nama usaha, no. HP, dll). Data ini hanya diisi sekali dan akan dipakai otomatis saat posting loker berikutnya.
                    </p>
                </div>

                <!-- Submit -->
                <button type="submit" id="btn-register" class="w-full py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-bold rounded-xl hover:from-pink-700 hover:to-rose-700 shadow-lg shadow-pink-500/35 hover:shadow-pink-500/50 transition-all transform hover:-translate-y-0.5 active:translate-y-0 text-sm">
                    <i class="fa-solid fa-store mr-2"></i> Buat Akun Usaha Gratis
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm font-semibold text-slate-500">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-pink-600 font-black hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>

        <!-- Note pencari kerja -->
        <div class="mt-5 text-center p-4 bg-white/70 rounded-2xl border border-pink-100">
            <p class="text-xs font-bold text-slate-500">
                <i class="fa-solid fa-info-circle text-pink-400 mr-1"></i>
                Pencari kerja tidak perlu daftar. <a href="{{ route('home') }}" class="text-pink-600 font-black">Langsung cari loker →</a>
            </p>
        </div>
    </div>
</body>
</html>

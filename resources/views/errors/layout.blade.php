<!DOCTYPE html>
<html lang="id" class="h-full bg-pink-50/60">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terjadi kesalahan') - LokerUMKM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="min-h-full font-sans antialiased bg-pink-50/60 flex items-center justify-center p-4">
    <div class="w-full max-w-md text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-6">
            <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-500 to-rose-500 text-white flex items-center justify-center shadow-lg shadow-pink-500/30">
                <i class="fa-solid fa-briefcase"></i>
            </span>
            <span class="text-xl font-extrabold text-slate-900">Loker<span class="text-pink-500">UMKM</span></span>
        </a>

        <div class="bg-white rounded-2xl border border-pink-100 shadow-lg p-8">
            <div class="w-16 h-16 mx-auto rounded-2xl @yield('badge_class', 'bg-pink-100 text-pink-500') flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid @yield('icon', 'fa-circle-exclamation')"></i>
            </div>
            <p class="text-5xl font-extrabold text-slate-900 tracking-tight">@yield('code', '—')</p>
            <h1 class="text-lg font-extrabold text-slate-800 mt-2">@yield('heading', 'Terjadi kesalahan')</h1>
            <p class="text-sm text-slate-500 font-medium mt-2">@yield('message')</p>

            <div class="flex flex-col sm:flex-row gap-2 mt-6">
                <a href="{{ route('home') }}" class="flex-1 py-2.5 bg-gradient-to-r from-pink-600 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-pink-700 hover:to-rose-700 transition-all">
                    <i class="fa-solid fa-house mr-1.5"></i> Beranda
                </a>
                <a href="{{ url()->previous() }}" class="flex-1 py-2.5 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
                </a>
            </div>
        </div>

        <p class="text-[11px] text-slate-600 font-medium mt-4">Info Loker UMKM · {{ date('Y') }}</p>
    </div>
</body>
</html>

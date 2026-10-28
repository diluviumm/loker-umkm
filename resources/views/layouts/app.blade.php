<!DOCTYPE html>
<html lang="id" class="h-full bg-pink-50/60">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Info Loker UMKM - Portal Lowongan Kerja Terpercaya')</title>
    @yield('meta')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Outfit"', '"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .hero-pink-gradient {
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 45%, #fecdd3 100%);
        }

        .card-pink-shadow {
            box-shadow: 0 10px 30px -12px rgba(244, 63, 94, 0.22);
        }

        .card-premium-glow {
            box-shadow: 0 10px 30px -10px rgba(251, 146, 60, 0.35);
        }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased text-slate-800 bg-pink-50/50 selection:bg-pink-400 selection:text-white">

    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b border-pink-200/80 glass-header">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-3 h-16 sm:h-20">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-500 via-rose-400 to-pink-400 flex items-center justify-center shadow-lg shadow-pink-500/30 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-briefcase text-white text-lg"></i>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                            Loker<span class="bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text text-transparent">UMKM</span>
                        </span>
                        <span class="text-[9px] sm:text-[10px] font-bold tracking-widest text-pink-600 uppercase mt-1">Portal Kerja UMKM</span>
                    </div>
                </a>

                <!-- Nav -->
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:text-pink-600 hover:bg-pink-100/70 transition-all hidden sm:inline-flex items-center gap-2">
                        <i class="fa-solid fa-house text-pink-500"></i> Beranda
                    </a>

                    @auth
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold text-amber-900 bg-amber-100 hover:bg-amber-200 transition-all border border-amber-300 inline-flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-amber-600"></i>
                                <span class="hidden sm:inline">Dashboard Admin</span>
                            </a>
                        @else
                            <a href="{{ route('business.dashboard') }}" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold text-pink-700 bg-pink-100 hover:bg-pink-200 transition-all border border-pink-300 inline-flex items-center gap-2">
                                <i class="fa-solid fa-store text-pink-500"></i>
                                <span class="hidden sm:inline">Dashboard Saya</span>
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all border border-slate-200 inline-flex items-center gap-2" title="Keluar">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span class="hidden md:inline">Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:text-pink-600 hover:bg-pink-100/60 transition-all hidden md:inline-flex items-center gap-2">
                            <i class="fa-solid fa-right-to-bracket text-pink-500"></i> Masuk Usaha
                        </a>
                    @endauth

                    <!-- Bookmark -->
                    <button x-data @click="$dispatch('open-bookmark-modal')" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 transition-all inline-flex items-center gap-2 border border-rose-200" title="Loker tersimpan">
                        <i class="fa-solid fa-heart"></i>
                        <span class="hidden lg:inline">Tersimpan</span>
                    </button>

                    <!-- Pasang Loker -->
                    @auth
                        @if(auth()->user()->isPemilikUsaha())
                            <a href="{{ route('business.jobs.create') }}" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2.5 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 rounded-xl shadow-md shadow-pink-500/30 transition-all">
                                <i class="fa-solid fa-plus-circle"></i>
                                <span class="hidden sm:inline">Pasang Loker</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2.5 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 rounded-xl shadow-md shadow-pink-500/30 transition-all">
                            <i class="fa-solid fa-plus-circle"></i>
                            <span class="hidden sm:inline">Pasang Loker</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Message -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-start justify-between gap-4 p-4 bg-emerald-50 border border-emerald-300 text-emerald-950 rounded-2xl shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/30">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="text-sm font-bold pt-1.5">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-900 p-1 transition-colors" aria-label="Tutup notifikasi">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-start justify-between gap-4 p-4 bg-rose-50 border border-rose-300 text-rose-950 rounded-2xl shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-rose-500/30">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <p class="text-sm font-bold pt-1.5">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-rose-600 hover:text-rose-900 p-1 transition-colors" aria-label="Tutup notifikasi">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 bg-rose-50 border border-rose-300 text-rose-950 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2.5 font-bold text-sm mb-2 text-rose-900">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span>Terdapat isian yang perlu diperbaiki:</span>
                </div>
                <ul class="list-disc list-inside text-sm font-medium space-y-1 text-rose-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-16 bg-slate-900 text-slate-300">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                <div class="md:col-span-5 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-500 to-rose-500 text-white flex items-center justify-center font-bold shadow-lg shadow-pink-500/30">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <span class="text-xl font-extrabold text-white tracking-tight">Loker<span class="text-pink-400">UMKM</span></span>
                    </div>
                    <p class="text-sm text-slate-300 leading-relaxed max-w-md">
                        Direktori lowongan kerja UMKM. Membantu usaha kecil menemukan pekerja lokal terbaik, langsung lewat WhatsApp.
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <span class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-pink-400">
                            <i class="fa-brands fa-instagram"></i>
                        </span>
                        <span class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-pink-400">
                            <i class="fa-brands fa-tiktok"></i>
                        </span>
                        <span class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400">
                            <i class="fa-brands fa-whatsapp"></i>
                        </span>
                    </div>
                </div>

                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-xs font-bold text-pink-400 uppercase tracking-widest">Navigasi</h4>
                    <ul class="space-y-2 text-sm font-semibold">
                        <li><a href="{{ route('home') }}" class="hover:text-pink-300 transition-colors"><i class="fa-solid fa-chevron-right text-[10px] text-pink-400 mr-1.5"></i>Cari Lowongan</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-pink-300 transition-colors"><i class="fa-solid fa-chevron-right text-[10px] text-pink-400 mr-1.5"></i>Pasang Loker UMKM</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-pink-300 transition-colors"><i class="fa-solid fa-chevron-right text-[10px] text-pink-400 mr-1.5"></i>Login Pemilik Usaha</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-pink-300 transition-colors"><i class="fa-solid fa-chevron-right text-[10px] text-pink-400 mr-1.5"></i>Login Admin</a></li>
                    </ul>
                </div>

                <div class="md:col-span-4 space-y-3">
                    <h4 class="text-xs font-bold text-pink-400 uppercase tracking-widest">Fitur Unggulan</h4>
                    <ul class="space-y-2 text-sm font-semibold">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-pink-400"></i> Moderasi loker oleh admin</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-pink-400"></i> Prioritas loker premium</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-400"></i> Lamar langsung via WhatsApp</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-pink-400"></i> Auto-arsip setelah 30 hari</li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-slate-800 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400 font-medium">
                <p>&copy; {{ date('Y') }} Info Loker UMKM. Dibuat untuk kemajuan UMKM Indonesia.</p>
                <div class="flex items-center gap-5">
                    <span>Privasi</span>
                    <span>Ketentuan</span>
                    <span>Bantuan</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bookmark Modal -->
    <div x-data="{
            isOpen: false,
            savedJobs: [],
            init() {
                this.loadJobs();
                window.addEventListener('open-bookmark-modal', () => {
                    this.loadJobs();
                    this.isOpen = true;
                });
                window.addEventListener('jobs-bookmarked', () => {
                    this.loadJobs();
                });
            },
            loadJobs() {
                this.savedJobs = JSON.parse(localStorage.getItem('bookmarked_jobs') || '[]');
            },
            removeJob(id) {
                this.savedJobs = this.savedJobs.filter(j => j.id != id);
                localStorage.setItem('bookmarked_jobs', JSON.stringify(this.savedJobs));
                window.dispatchEvent(new CustomEvent('jobs-bookmarked'));
            }
        }"
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        @keydown.escape.window="isOpen = false"
    >
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col max-h-[85vh]" @click.away="isOpen = false">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-rose-50 to-white">
                <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2"><i class="fa-solid fa-heart text-rose-500"></i> Loker Tersimpan</h3>
                <button @click="isOpen = false" class="text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center transition-all" aria-label="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-5 overflow-y-auto flex-1 bg-slate-50">
                <template x-if="savedJobs.length === 0">
                    <div class="text-center py-8">
                        <i class="fa-regular fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
                        <p class="text-slate-500 font-bold text-sm">Belum ada loker yang disimpan.</p>
                        <p class="text-xs text-slate-400 mt-1">Klik tombol hati pada loker untuk menyimpannya.</p>
                    </div>
                </template>
                <div class="space-y-3" x-show="savedJobs.length > 0">
                    <template x-for="job in savedJobs" :key="job.id">
                        <div class="bg-white p-3.5 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <a :href="job.url" class="font-bold text-slate-800 hover:text-rose-600 truncate block text-sm" x-text="job.posisi"></a>
                                <p class="text-xs font-semibold text-slate-500 truncate mt-0.5">
                                    <i class="fa-solid fa-building text-slate-400 mr-1"></i><span x-text="job.perusahaan"></span>
                                </p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <a :href="job.url" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all text-xs">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <button @click="removeJob(job.id)" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all text-xs" aria-label="Hapus dari tersimpan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="p-3 border-t border-slate-100 bg-white">
                <button @click="isOpen = false" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all w-full">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Analytics: klik tombol WhatsApp (delegated, dipakai semua halaman) -->
    <script>
        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-track-job]');
            if (!trigger) return;

            const jobId = trigger.getAttribute('data-track-job');
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!jobId || !token) return;

            fetch(`/loker/${jobId}/track-click`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                keepalive: true
            }).catch(() => {});
        });
    </script>
</body>
</html>

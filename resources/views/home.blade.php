@extends('layouts.app')

@section('title', 'Info Loker UMKM - Cari Lowongan Kerja Terpercaya & Terbaru')

@section('content')

<!-- Hero & Search -->
<section class="relative hero-pink-gradient text-slate-900 pt-10 pb-20 overflow-hidden border-b border-pink-200/50">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[320px] bg-pink-300/30 blur-[110px] rounded-full pointer-events-none"></div>

    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/95 border border-pink-300 text-pink-700 text-[11px] sm:text-xs font-bold uppercase tracking-wider mb-6 shadow-sm">
            <i class="fa-solid fa-circle-check text-amber-500"></i> Platform Lowongan Kerja UMKM Terverifikasi
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 max-w-4xl mx-auto leading-tight">
            Temukan Pekerjaan Impian &amp; Hubungi UMKM
            <span class="bg-gradient-to-r from-pink-600 via-rose-600 to-pink-500 bg-clip-text text-transparent">Langsung via WhatsApp</span>
        </h1>

        <p class="mt-4 text-sm sm:text-base lg:text-lg text-slate-700 max-w-2xl mx-auto font-medium leading-relaxed">
            Lowongan kerja lokal tanpa ribet. Tanpa CV rumit — lamar ke pemilik usaha hanya dengan satu klik via WhatsApp.
        </p>

        <!-- Search -->
        <div class="mt-8 max-w-5xl mx-auto">
            <form action="{{ route('home') }}" method="GET" role="search" class="bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl shadow-pink-500/10 border border-pink-200 text-slate-800 grid grid-cols-1 md:grid-cols-12 gap-3 items-center">

                <!-- Keyword -->
                <div class="md:col-span-4 relative">
                    <label for="q" class="sr-only">Cari posisi atau nama usaha</label>
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-pink-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Posisi (Guru, IT, Admin, Kasir...)"
                        class="w-full pl-10 pr-3 py-2.5 bg-pink-50/60 hover:bg-pink-50 focus:bg-white border border-pink-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-500 focus:ring-4 focus:ring-pink-200 focus:border-pink-400 outline-none transition-all">
                </div>

                <!-- Kategori -->
                <div class="md:col-span-3 relative">
                    <label for="kategori" class="sr-only">Kategori</label>
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-pink-400">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <select id="kategori" name="kategori" class="w-full pl-10 pr-8 py-2.5 bg-pink-50/60 hover:bg-pink-50 focus:bg-white border border-pink-200 rounded-xl text-sm font-semibold text-slate-900 focus:ring-4 focus:ring-pink-200 focus:border-pink-400 outline-none transition-all appearance-none cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('kategori') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-pink-400 text-xs">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="md:col-span-3 relative">
                    <label for="lokasi" class="sr-only">Lokasi</label>
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-pink-400">
                        <i class="fa-solid fa-location-dot text-sm"></i>
                    </div>
                    <input type="text" id="lokasi" name="lokasi" value="{{ request('lokasi') }}" list="kota-indonesia" placeholder="Lokasi di Indonesia..."
                        class="w-full pl-10 pr-3 py-2.5 bg-pink-50/60 hover:bg-pink-50 focus:bg-white border border-pink-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-500 focus:ring-4 focus:ring-pink-200 focus:border-pink-400 outline-none transition-all">
                    <datalist id="kota-indonesia">
                        <option value="Jakarta"></option>
                        <option value="Bandung"></option>
                        <option value="Surabaya"></option>
                        <option value="Yogyakarta"></option>
                        <option value="Semarang"></option>
                        <option value="Medan"></option>
                        <option value="Bali"></option>
                        <option value="Malang"></option>
                        <option value="Makassar"></option>
                        <option value="Palembang"></option>
                        <option value="Tangerang"></option>
                        <option value="Bekasi"></option>
                        <option value="Bogor"></option>
                        <option value="Depok"></option>
                        <option value="Solo"></option>
                    </datalist>
                </div>

                <!-- Submit -->
                <div class="md:col-span-2">
                    <button type="submit" class="w-full py-2.5 px-4 font-bold text-white bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 rounded-xl shadow-md shadow-pink-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-search"></i>
                        <span>Cari</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</section>

<!-- Category Pills -->
<section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-20">
    <div class="bg-white rounded-2xl p-3 shadow-lg shadow-pink-500/10 border border-pink-200/90 flex items-center gap-2 overflow-x-auto no-scrollbar lg:flex-wrap lg:overflow-visible">

        <a href="{{ route('home') }}" class="px-3.5 py-1.5 rounded-lg text-[11px] sm:text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 shrink-0 {{ !request('kategori') ? 'bg-slate-900 text-white shadow-md' : 'bg-pink-50 text-slate-800 hover:bg-pink-100 border border-pink-200' }}">
            <i class="fa-solid fa-border-all text-pink-500 {{ !request('kategori') ? 'text-white' : '' }}"></i> Semua Loker
        </a>

        @foreach($categories as $cat)
            <a href="{{ route('home', array_merge(request()->except('kategori'), ['kategori' => $cat->slug])) }}"
               class="px-3.5 py-1.5 rounded-lg text-[11px] sm:text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 shrink-0 {{ request('kategori') == $cat->slug ? 'bg-pink-600 text-white shadow-md shadow-pink-600/30' : 'bg-pink-50 text-slate-800 hover:bg-pink-100 border border-pink-200' }}">
                <i class="fa-solid {{ $cat->icon }} text-pink-500 {{ request('kategori') == $cat->slug ? 'text-white' : '' }}"></i>
                <span>{{ $cat->nama }}</span>
                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ request('kategori') == $cat->slug ? 'bg-white/25 text-white' : 'bg-pink-200 text-pink-950' }}">{{ $cat->job_listings_count }}</span>
            </a>
        @endforeach

    </div>
</section>

<!-- Job Listings -->
<section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Lowongan Kerja Aktif</h2>
            <p class="text-sm text-pink-700 font-semibold mt-1">Loker <strong>Premium</strong> ditampilkan paling atas.</p>
        </div>
        <div class="text-sm font-bold text-pink-900 bg-white border border-pink-300 px-4 py-2 rounded-xl shadow-sm self-start sm:self-auto flex items-center gap-2">
            <i class="fa-solid fa-briefcase text-pink-500"></i> {{ $jobs->total() }} lowongan tayang
        </div>
    </div>

    @if($jobs->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-pink-200 shadow-sm">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-pink-100 flex items-center justify-center text-pink-400 text-2xl mb-4">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800">Tidak ada lowongan ditemukan</h3>
            <p class="text-sm text-slate-500 font-medium mt-1 max-w-md mx-auto">
                Coba ubah kata kunci pencarian atau pilih kategori lain.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-rotate-left"></i> Reset Filter
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 items-stretch">
            @foreach($jobs as $job)
                <article class="group bg-white rounded-2xl border transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between overflow-hidden {{ $job->is_premium ? 'border-amber-300 card-premium-glow ring-2 ring-amber-200/60' : 'border-pink-100 card-pink-shadow hover:border-pink-300' }}">

                    <div class="{{ $job->is_premium ? 'bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950' : 'bg-pink-100/80 text-pink-900 border-b border-pink-200/60' }} text-[11px] uppercase tracking-wide py-2 px-4 flex items-center justify-between font-bold">
                        @if($job->is_premium)
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-crown"></i> Loker Premium</span>
                            <span class="text-[10px] bg-slate-950/15 px-2 py-0.5 rounded-full">Rekomendasi</span>
                        @else
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-pink-500"></i> Loker Terverifikasi</span>
                            <span class="text-[10px] text-pink-700">{{ $job->created_at->diffForHumans() }}</span>
                        @endif
                    </div>

                    <div class="p-5 flex-grow flex flex-col gap-4">

                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-pink-800 bg-pink-100/90 px-2.5 py-1 rounded-lg border border-pink-200">
                                    <i class="fa-solid {{ $job->category->icon ?? 'fa-briefcase' }} text-pink-500"></i>
                                    <span>{{ $job->category->nama ?? 'Umum' }}</span>
                                </span>

                                @if($job->is_premium)
                                    <span class="text-[10px] font-semibold text-slate-500">{{ $job->created_at->diffForHumans() }}</span>
                                @endif
                            </div>

                            <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-pink-600 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->posisi }}</a>
                            </h3>

                            <p class="text-sm font-bold text-pink-800 flex items-center gap-1.5 mt-1.5">
                                <i class="fa-solid fa-store text-pink-500 text-xs"></i>
                                <span class="truncate">{{ $job->nama_usaha }}</span>
                            </p>
                        </div>

                        <div class="pt-3 border-t border-pink-100 space-y-2 text-xs font-semibold text-slate-700">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-location-dot"></i></span>
                                <span class="truncate">{{ $job->lokasi }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-clock"></i></span>
                                <span>{{ $job->tipe_pekerjaan }}</span>
                            </div>
                            @if($job->gaji)
                                <div class="flex items-center gap-2 text-emerald-700">
                                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-money-bill-wave"></i></span>
                                    <span class="truncate">{{ $job->gaji }}</span>
                                </div>
                            @endif
                        </div>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed font-medium">
                            {{ Str::limit(strip_tags($job->deskripsi), 100) }}
                        </p>

                    </div>

                    <div class="p-3 bg-pink-50/70 border-t border-pink-100 grid grid-cols-2 gap-2">
                        <a href="{{ route('jobs.show', $job->slug) }}" class="py-2.5 px-3 bg-white hover:bg-pink-100/60 text-pink-950 text-xs font-bold rounded-lg border border-pink-200 text-center transition-colors">
                            Detail
                        </a>
                        <a href="{{ $job->wa_link }}" target="_blank" rel="noopener" data-track-job="{{ $job->id }}"
                           class="inline-flex items-center justify-center gap-2 py-2.5 px-3 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-lg shadow-sm shadow-emerald-600/30 transition-all">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Lamar WA</span>
                        </a>
                    </div>

                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $jobs->links() }}
        </div>
    @endif

</section>

@endsection

@extends('layouts.app')

@section('title', $job->posisi . ' - ' . $job->nama_usaha . ' | Info Loker UMKM')
@section('description', Str::limit(strip_tags($job->deskripsi), 155))

@section('meta')
    <meta property="og:title" content="{{ $job->posisi }} di {{ $job->nama_usaha }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($job->deskripsi), 150) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('jobs.show', $job->slug) }}">
    <meta property="og:site_name" content="LokerUMKM Kaltim">
    <meta property="og:image" content="{{ asset('images/og-default.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $job->posisi }} di {{ $job->nama_usaha }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($job->deskripsi), 150) }}">
@endsection

@section('content')

<!-- Header -->
<div class="hero-pink-gradient text-slate-900 pt-8 pb-16 relative overflow-hidden border-b border-pink-200/50">
    <div class="absolute top-0 right-0 w-[420px] h-[260px] bg-pink-300/30 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-600 mb-6 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-pink-600 transition-colors">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-pink-400"></i>
            <span class="text-slate-700">{{ $job->category->nama ?? 'Loker' }}</span>
            <i class="fa-solid fa-chevron-right text-[9px] text-pink-400"></i>
            <span class="text-pink-700 font-bold truncate max-w-[16rem]">{{ $job->posisi }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    @if($job->is_premium)
                        <span class="px-3 py-1 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 text-[11px] font-bold rounded-full uppercase tracking-wide shadow-sm">
                            <i class="fa-solid fa-crown mr-1"></i> Loker Premium
                        </span>
                    @endif
                    <span class="px-3 py-1 bg-white/90 text-pink-800 text-[11px] font-bold rounded-full border border-pink-300 shadow-sm">
                        <i class="fa-solid {{ $job->category->icon ?? 'fa-briefcase' }} mr-1 text-pink-500"></i> {{ $job->category->nama ?? 'Umum' }}
                    </span>
                    <span class="px-3 py-1 bg-pink-100 text-pink-900 text-[11px] font-bold rounded-full border border-pink-200">
                        {{ $job->tipe_pekerjaan }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight">
                    {{ $job->posisi }}
                </h1>

                <p class="text-base sm:text-lg font-bold text-pink-700 flex items-center gap-2">
                    <i class="fa-solid fa-store text-pink-500"></i>
                    <span>{{ $job->nama_usaha }}</span>
                </p>
            </div>

            <!-- Quick Contact -->
            <div class="bg-white/95 backdrop-blur-md p-5 rounded-2xl border border-pink-200 flex flex-col items-center shrink-0 text-center shadow-lg max-w-sm w-full lg:w-auto">
                <span class="text-xs text-slate-600 font-semibold">Lamar pekerjaan ini</span>

                @auth
                    <a href="{{ $job->wa_link }}" target="_blank" rel="noopener" data-track-job="{{ $job->id }}"
                       class="mt-3 inline-flex items-center gap-2 px-5 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-600/30 transition-all w-full justify-center">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                        <span>Lamar via WhatsApp</span>
                    </a>
                @else
                    <a href="{{ route('register.pelamar') }}"
                       class="mt-3 inline-flex items-center gap-2 px-5 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm rounded-xl shadow-md transition-all w-full justify-center">
                        <i class="fa-solid fa-lock text-amber-300"></i>
                        <span>Buat akun untuk lihat WA</span>
                    </a>
                    <p class="text-[10px] text-slate-500 font-semibold mt-2">Gratis · cukup email &amp; password</p>
                @endauth

                <div class="flex items-center gap-2 w-full mt-3">
                    @php
                        $isSeeker = auth()->check() && auth()->user()->isPelamar();
                        $isSaved = $isSeeker && auth()->user()->savedJobs()->whereKey($job->id)->exists();
                    @endphp

                    @if($isSeeker)
                        <form action="{{ route($isSaved ? 'pelamar.saved.destroy' : 'pelamar.saved.store', $job) }}" method="POST" class="flex-1">
                            @csrf
                            @if($isSaved) @method('DELETE') @endif
                            <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-xs transition-all border inline-flex items-center justify-center gap-1.5 {{ $isSaved ? 'bg-rose-500 text-white border-rose-500' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' }}">
                                <i class="fa-solid fa-heart"></i> {{ $isSaved ? 'Tersimpan' : 'Simpan' }}
                            </button>
                        </form>
                    @else
                    <button
                        x-data="{
                            isSaved: false,
                            init() {
                                this.sync();
                                window.addEventListener('jobs-bookmarked', () => this.sync());
                            },
                            sync() {
                                const saved = JSON.parse(localStorage.getItem('bookmarked_jobs') || '[]');
                                this.isSaved = saved.some(j => String(j.id) === this.$el.dataset.id);
                            },
                            toggleSave() {
                                const d = this.$el.dataset;
                                let saved = JSON.parse(localStorage.getItem('bookmarked_jobs') || '[]');
                                if (this.isSaved) {
                                    saved = saved.filter(j => String(j.id) !== d.id);
                                } else {
                                    saved.push({ id: d.id, posisi: d.posisi, perusahaan: d.usaha, url: d.url });
                                }
                                localStorage.setItem('bookmarked_jobs', JSON.stringify(saved));
                                this.isSaved = !this.isSaved;
                                window.dispatchEvent(new CustomEvent('jobs-bookmarked'));
                            }
                        }"
                        data-id="{{ $job->id }}"
                        data-posisi="{{ $job->posisi }}"
                        data-usaha="{{ $job->nama_usaha }}"
                        data-url="{{ route('jobs.show', $job->slug) }}"
                        @click="toggleSave()"
                        :class="isSaved ? 'bg-rose-500 text-white border-rose-500' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100'"
                        class="flex-1 py-2.5 rounded-xl font-bold text-xs transition-all border inline-flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-heart"></i> <span x-text="isSaved ? 'Tersimpan' : 'Simpan'"></span>
                    </button>
                    @endif

                    <button
                        x-data="{
                            share() {
                                const d = this.$el.dataset;
                                const text = 'Lowongan ' + d.posisi + ' di ' + d.usaha + '\nLihat selengkapnya: ' + d.url;
                                window.open('https://api.whatsapp.com/send?text=' + encodeURIComponent(text), '_blank');
                            }
                        }"
                        data-posisi="{{ $job->posisi }}"
                        data-usaha="{{ $job->nama_usaha }}"
                        data-url="{{ route('jobs.show', $job->slug) }}"
                        @click="share()"
                        class="flex-1 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 rounded-xl font-bold text-xs transition-all inline-flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-share-nodes"></i> Bagikan
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Details -->
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-16 relative z-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Main -->
        <div class="lg:col-span-8 space-y-6">
            <article class="bg-white rounded-2xl p-6 sm:p-8 shadow-lg border border-pink-200/90">

                <h2 class="text-lg font-extrabold text-slate-900 border-b border-pink-100 pb-3 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-pink-500"></i> Deskripsi Pekerjaan
                </h2>

                <div class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $job->deskripsi }}
                </div>

                @if($job->syarat)
                    <h2 class="text-lg font-extrabold text-slate-900 border-b border-pink-100 pb-3 mb-4 mt-8 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-pink-500"></i> Persyaratan Pelamar
                    </h2>
                    <div class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $job->syarat }}
                    </div>
                @endif

                <div class="mt-8 p-6 bg-gradient-to-r from-pink-50 via-rose-50 to-pink-50 rounded-2xl border border-pink-200 text-center">
                    <h3 class="text-base font-extrabold text-pink-950">Tertarik mengisi posisi ini?</h3>
                    <p class="text-sm text-pink-800 font-medium mt-1 max-w-lg mx-auto">
                        Kontak WhatsApp pemilik usaha hanya terbuka untuk pelamar yang sudah punya akun.
                    </p>
                    @auth
                        <a href="{{ $job->wa_link }}" target="_blank" rel="noopener" data-track-job="{{ $job->id }}"
                           class="inline-flex items-center gap-2.5 mt-4 px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-600/30 transition-all">
                            <i class="fa-brands fa-whatsapp text-2xl"></i>
                            <span>Kirim Lamaran Sekarang (WA)</span>
                        </a>
                    @else
                        <a href="{{ route('register.pelamar') }}"
                           class="inline-flex items-center gap-2.5 mt-4 px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                            <i class="fa-solid fa-lock text-amber-300"></i>
                            <span>Buat Akun Pelamar (Gratis)</span>
                        </a>
                        <p class="text-[11px] text-pink-900/70 font-semibold mt-2">Sudah punya akun? <a href="{{ route('login') }}" class="underline font-extrabold">Masuk</a></p>
                    @endauth
                </div>

            </article>
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-pink-200/90 space-y-4 text-sm">
                <h2 class="font-extrabold text-slate-900 text-base border-b border-pink-100 pb-3">Ringkasan Loker</h2>

                <div>
                    <span class="text-slate-500 font-bold text-[10px] uppercase tracking-wider block">Nama UMKM</span>
                    <span class="font-extrabold text-slate-900">{{ $job->nama_usaha }}</span>
                </div>

                <div>
                    <span class="text-slate-500 font-bold text-[10px] uppercase tracking-wider block">Lokasi</span>
                    <span class="font-semibold text-slate-800 flex items-center gap-1.5 mt-0.5">
                        <i class="fa-solid fa-location-dot text-rose-500"></i> {{ $job->lokasi }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-500 font-bold text-[10px] uppercase tracking-wider block">Tipe Pekerjaan</span>
                    <span class="font-semibold text-slate-800">{{ $job->tipe_pekerjaan }}</span>
                </div>

                @if($job->gaji)
                    <div>
                        <span class="text-slate-500 font-bold text-[10px] uppercase tracking-wider block">Penawaran Gaji</span>
                        <span class="font-extrabold text-emerald-700">{{ $job->gaji }}</span>
                    </div>
                @endif

                <div>
                    <span class="text-slate-500 font-bold text-[10px] uppercase tracking-wider block">Tanggal Tayang</span>
                    <span class="font-semibold text-slate-700">{{ $job->approved_at ? $job->approved_at->translatedFormat('d M Y') : $job->created_at->translatedFormat('d M Y') }}</span>
                </div>

                <div>
                    <span class="text-slate-500 font-bold text-[10px] uppercase tracking-wider block">Nomor WhatsApp</span>
                    @auth
                        <span class="font-mono font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200 inline-block mt-1">{{ $job->nomor_wa }}</span>
                    @else
                        <span class="font-mono font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 inline-block mt-1 select-none">•••• •••• ••••</span>
                        <a href="{{ route('register.pelamar') }}" class="block text-[10px] font-bold text-pink-600 hover:text-pink-700 mt-1">
                            <i class="fa-solid fa-lock mr-1"></i>Buat akun untuk membuka
                        </a>
                    @endauth
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 text-xs font-semibold text-slate-500">
                    <span><i class="fa-solid fa-eye text-blue-500 mr-1"></i>{{ number_format($job->views_count) }} dilihat</span>
                    <span><i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i>{{ number_format($job->clicks_count) }} klik</span>
                </div>
            </div>

            <div class="bg-pink-100/80 rounded-2xl p-5 border border-pink-300 text-sm text-pink-950 font-medium">
                <strong class="font-extrabold flex items-center gap-2 text-pink-900 mb-1">
                    <i class="fa-solid fa-shield-halved text-pink-600"></i> Hati-hati penipuan
                </strong>
                Info Loker UMKM tidak pernah meminta biaya apa pun dari pelamar. Jangan mentransfer uang untuk alasan apa pun.
            </div>
        </aside>

    </div>

    @if($relatedJobs->isNotEmpty())
        <section class="mt-10">
            <h2 class="text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-pink-500"></i> Loker serupa
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relatedJobs as $related)
                    <a href="{{ route('jobs.show', $related->slug) }}" class="group bg-white rounded-2xl p-4 border border-pink-100 hover:border-pink-300 shadow-sm hover:shadow-md transition-all">
                        <span class="text-[10px] font-bold text-pink-700 bg-pink-100 px-2 py-0.5 rounded-full">{{ $related->category->nama ?? 'Umum' }}</span>
                        <h3 class="text-sm font-extrabold text-slate-900 mt-2 group-hover:text-pink-600 line-clamp-2">{{ $related->posisi }}</h3>
                        <p class="text-xs font-semibold text-slate-500 mt-1 truncate"><i class="fa-solid fa-store mr-1"></i>{{ $related->nama_usaha }}</p>
                        <p class="text-xs font-semibold text-slate-500 mt-1 truncate"><i class="fa-solid fa-location-dot text-rose-400 mr-1"></i>{{ $related->lokasi }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection

@extends('layouts.app')

@section('title', 'Dashboard Usaha Saya - ' . ($profile?->nama_usaha ?? 'LokerUMKM'))

@section('content')
<div class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-8">

    {{-- ── Header Selamat Datang ─────────────────────────────────────── --}}
    <div class="mb-6 p-5 bg-gradient-to-r from-pink-600 to-rose-600 rounded-2xl text-white shadow-lg shadow-pink-500/25">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <p class="text-pink-100 font-bold text-sm mb-1">Selamat datang kembali 👋</p>
                <h1 class="text-xl sm:text-2xl font-extrabold">{{ $profile?->nama_usaha ?? auth()->user()->name }}</h1>
                @if($profile?->alamat_usaha)
                    <p class="text-pink-100 font-semibold text-sm mt-1"><i class="fa-solid fa-location-dot mr-1"></i>{{ $profile->alamat_usaha }}</p>
                @endif
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('business.jobs.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-pink-600 font-bold rounded-xl hover:bg-pink-50 transition-all shadow-md text-xs">
                    <i class="fa-solid fa-plus-circle"></i> Pasang Loker Baru
                </a>
                <a href="{{ route('business.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white font-bold rounded-xl hover:bg-white/30 transition-all border border-white/40 text-xs">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    {{-- ── Peringatan Profil Belum Lengkap ──────────────────────────── --}}
    @if(!$profile || !$profile->profil_lengkap)
        <div class="mb-5 p-4 bg-amber-50 border border-amber-300 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <div class="w-12 h-12 bg-amber-400 rounded-2xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-black text-amber-900 text-sm">Profil usaha belum dilengkapi!</p>
                <p class="text-amber-700 font-semibold text-xs mt-0.5">Lengkapi data usaha Anda terlebih dahulu agar bisa mulai posting loker.</p>
            </div>
            <a href="{{ route('business.profile.edit') }}" class="px-5 py-2.5 bg-amber-400 text-amber-900 font-black rounded-xl hover:bg-amber-500 transition-all text-sm whitespace-nowrap">
                Lengkapi Sekarang →
            </a>
        </div>
    @endif

    {{-- ── Statistik ─────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @php
            $statItems = [
                ['label' => 'Total Loker', 'value' => $stats['total'], 'icon' => 'fa-briefcase', 'bg' => 'bg-slate-100', 'fg' => 'text-slate-600'],
                ['label' => 'Aktif', 'value' => $stats['aktif'], 'icon' => 'fa-circle-check', 'bg' => 'bg-emerald-100', 'fg' => 'text-emerald-600'],
                ['label' => 'Menunggu', 'value' => $stats['pending'], 'icon' => 'fa-clock', 'bg' => 'bg-amber-100', 'fg' => 'text-amber-600'],
                ['label' => 'Ditolak', 'value' => $stats['ditolak'], 'icon' => 'fa-circle-xmark', 'bg' => 'bg-rose-100', 'fg' => 'text-rose-600'],
                ['label' => 'Kadaluarsa', 'value' => $stats['kadaluarsa'], 'icon' => 'fa-calendar-xmark', 'bg' => 'bg-slate-100', 'fg' => 'text-slate-500'],
                ['label' => 'Premium', 'value' => $stats['premium'], 'icon' => 'fa-crown', 'bg' => 'bg-orange-100', 'fg' => 'text-orange-500'],
            ];
        @endphp
        @foreach($statItems as $stat)
            <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm text-center">
                <div class="w-10 h-10 rounded-xl {{ $stat['bg'] }} flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid {{ $stat['icon'] }} {{ $stat['fg'] }} text-base"></i>
                </div>
                <p class="text-xl font-extrabold text-slate-900">{{ $stat['value'] }}</p>
                <p class="text-xs font-bold text-slate-500 mt-0.5">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ── Daftar Loker Saya ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-lg font-black text-slate-900">
                <i class="fa-solid fa-list-check text-pink-500 mr-2"></i> Loker yang Saya Posting
            </h2>
            @if($profile?->profil_lengkap)
                <a href="{{ route('business.jobs.create') }}" class="text-sm font-black text-pink-600 hover:text-pink-700 flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Tambah Loker
                </a>
            @endif
        </div>

        @if($lokers->isEmpty())
            <div class="text-center py-16 px-6">
                <div class="w-20 h-20 bg-pink-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-briefcase text-pink-300 text-3xl"></i>
                </div>
                <p class="text-lg font-black text-slate-700">Belum ada loker yang diposting</p>
                <p class="text-slate-400 font-semibold text-sm mt-1 mb-6">Mulai pasang loker pertama Anda sekarang!</p>
                @if($profile?->profil_lengkap)
                    <a href="{{ route('business.jobs.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 transition-all text-sm">
                        <i class="fa-solid fa-plus-circle"></i> Pasang Loker Pertama
                    </a>
                @else
                    <a href="{{ route('business.profile.edit') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-400 text-amber-900 font-black rounded-2xl text-sm">
                        <i class="fa-solid fa-store"></i> Lengkapi Profil Usaha Dulu
                    </a>
                @endif
            </div>
        @else
            <div class="divide-y divide-slate-50">
                @foreach($lokers as $loker)
                    <div class="px-4 py-3.5 hover:bg-pink-50/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h3 class="font-black text-slate-900 text-sm truncate">{{ $loker->posisi }}</h3>
                                    @if($loker->is_premium)
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 font-bold rounded-full text-[10px] border border-amber-200 flex items-center gap-1">
                                            <i class="fa-solid fa-crown text-amber-500"></i> Premium aktif
                                        </span>
                                    @elseif($loker->premium_requested)
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold rounded-full text-[10px] border border-amber-200 flex items-center gap-1">
                                            <i class="fa-solid fa-hourglass-half text-amber-500"></i> Pengajuan premium
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold flex-wrap">
                                    <span><i class="fa-solid fa-tag text-pink-400 mr-1"></i>{{ $loker->category?->nama ?? '-' }}</span>
                                    <span><i class="fa-solid fa-location-dot text-pink-400 mr-1"></i>{{ $loker->lokasi }}</span>
                                    <span><i class="fa-solid fa-calendar text-pink-400 mr-1"></i>{{ $loker->created_at->diffForHumans() }}</span>
                                    @if($loker->expires_at && $loker->status === 'aktif')
                                        <span class="text-amber-600"><i class="fa-solid fa-hourglass-half mr-1"></i>Berakhir {{ $loker->expires_at->translatedFormat('d M Y') }}</span>
                                    @endif
                                    <span class="text-blue-500"><i class="fa-solid fa-eye mr-1"></i>{{ $loker->views_count }} dilihat</span>
                                    <span class="text-emerald-500"><i class="fa-brands fa-whatsapp mr-1"></i>{{ $loker->clicks_count }} klik WA</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                {{-- Status Badge --}}
                                @php
                                    $statusConfig = [
                                        'aktif'      => ['bg-emerald-100 text-emerald-700 border-emerald-200', 'fa-circle-check', 'Aktif'],
                                        'pending'    => ['bg-amber-100 text-amber-700 border-amber-200', 'fa-clock', 'Menunggu Review'],
                                        'ditolak'    => ['bg-rose-100 text-rose-700 border-rose-200', 'fa-circle-xmark', 'Ditolak'],
                                        'kadaluarsa' => ['bg-slate-100 text-slate-600 border-slate-200', 'fa-calendar-xmark', 'Kadaluarsa'],
                                    ];
                                    [$badgeClass, $badgeIcon, $badgeLabel] = $statusConfig[$loker->status] ?? ['bg-slate-100 text-slate-600 border-slate-200', 'fa-circle', $loker->status];
                                @endphp
                                <span class="px-3 py-1.5 {{ $badgeClass }} font-black rounded-xl text-xs border flex items-center gap-1.5">
                                    <i class="fa-solid {{ $badgeIcon }}"></i> {{ $badgeLabel }}
                                </span>

                                {{-- Aksi --}}
                                @if($loker->status === 'aktif')
                                    <a href="{{ route('jobs.show', $loker->slug) }}" target="_blank" title="Lihat Loker" aria-label="Lihat loker" class="px-3.5 py-2.5 bg-blue-50 text-blue-600 font-black rounded-xl text-xs border border-blue-200 hover:bg-blue-100 transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('business.jobs.close', $loker) }}" method="POST" class="inline-block" onsubmit="return confirm('Tandai loker ini sebagai sudah terisi/tutup?')">
                                        @csrf
                                        <button type="submit" title="Tutup Loker" aria-label="Tutup loker" class="px-3.5 py-2.5 bg-slate-50 text-slate-600 font-black rounded-xl text-xs border border-slate-200 hover:bg-slate-100 transition-colors">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($loker->status === 'kadaluarsa')
                                    <form action="{{ route('business.jobs.renew', $loker) }}" method="POST" class="inline-block" onsubmit="return confirm('Ajukan perpanjangan tayang loker ini?')">
                                        @csrf
                                        <button type="submit" title="Perpanjang" aria-label="Perpanjang loker" class="px-3.5 py-2.5 bg-emerald-50 text-emerald-600 font-black rounded-xl text-xs border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                            <i class="fa-solid fa-rotate-right"></i> Perpanjang
                                        </button>
                                    </form>
                                @endif
                                @if(in_array($loker->status, ['pending', 'ditolak']))
                                    <form action="{{ route('business.jobs.destroy', $loker) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus loker ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus" aria-label="Hapus loker" class="px-3.5 py-2.5 bg-rose-50 text-rose-600 font-black rounded-xl text-xs border border-rose-200 hover:bg-rose-100 transition-colors">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $lokers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

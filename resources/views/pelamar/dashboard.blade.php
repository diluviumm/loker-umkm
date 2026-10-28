@extends('layouts.app')

@section('title', 'Dashboard Pelamar - LokerUMKM')

@section('content')
<div class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-6 p-5 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl text-white shadow-lg shadow-emerald-600/25">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-emerald-100 font-semibold text-xs mb-1">Selamat datang kembali 👋</p>
                <h1 class="text-xl sm:text-2xl font-extrabold">{{ $user->name }}</h1>
                <p class="text-emerald-100 font-medium text-xs mt-1">
                    <i class="fa-solid fa-user-tag mr-1"></i>{{ $user->roleLabel() }}
                    @if($user->phone)
                        · <i class="fa-brands fa-whatsapp mr-1"></i>{{ $user->phone }}
                    @endif
                </p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition-all text-xs">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Loker Baru
            </a>
        </div>
    </div>

    <div class="flex items-center justify-between mb-4" id="tersimpan">
        <h2 class="text-lg font-extrabold text-slate-900">
            <i class="fa-solid fa-heart text-rose-500 mr-2"></i>Loker Tersimpan
        </h2>
        <span class="text-xs font-bold text-slate-500">{{ $savedJobs->total() }} loker</span>
    </div>

    @if($savedJobs->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-rose-50 flex items-center justify-center text-rose-300 text-2xl mb-4">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800">Belum ada loker tersimpan</h3>
            <p class="text-sm text-slate-500 font-medium mt-1">Buka detail loker lalu tekan tombol <strong>Simpan</strong>.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                <i class="fa-solid fa-magnifying-glass"></i> Jelajahi Loker
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($savedJobs as $job)
                <article class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-pink-800 bg-pink-100 px-2.5 py-1 rounded-lg border border-pink-200">
                            <i class="fa-solid {{ $job->category->icon ?? 'fa-briefcase' }} text-pink-500"></i> {{ $job->category->nama ?? 'Umum' }}
                        </span>
                        @if($job->is_premium)
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full"><i class="fa-solid fa-crown"></i> Premium</span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 line-clamp-2">{{ $job->posisi }}</h3>
                        <p class="text-xs font-bold text-pink-700 mt-1 truncate"><i class="fa-solid fa-store mr-1"></i>{{ $job->nama_usaha }}</p>
                        <p class="text-xs font-semibold text-slate-500 mt-1 truncate"><i class="fa-solid fa-location-dot text-rose-400 mr-1"></i>{{ $job->lokasi }}</p>
                    </div>

                    <div class="mt-auto flex items-center gap-2 pt-3 border-t border-slate-100">
                        <a href="{{ route('jobs.show', $job->slug) }}" class="flex-1 py-2 text-center text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Detail</a>
                        <a href="{{ $job->wa_link }}" target="_blank" rel="noopener" data-track-job="{{ $job->id }}"
                           class="flex-1 py-2 text-center text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg transition-colors">
                            <i class="fa-brands fa-whatsapp mr-1"></i>Lamar
                        </a>
                        <form action="{{ route('pelamar.saved.destroy', $job) }}" method="POST"
                              onsubmit="return confirm('Hapus loker ini dari daftar tersimpan?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus dari tersimpan" aria-label="Hapus dari tersimpan"
                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors flex items-center justify-center text-xs">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">{{ $savedJobs->links() }}</div>
    @endif
</div>
@endsection

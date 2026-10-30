@extends('layouts.app')

@section('title', 'Pengguna & Traffic - Admin LokerUMKM Kaltim')

@section('content')

<div class="hero-pink-gradient text-slate-900 py-8 border-b border-pink-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-4 py-1.5 bg-white/80 text-amber-900 text-xs font-black uppercase tracking-wider rounded-full border-2 border-amber-300 shadow-sm">
                    Traffic &amp; Pendaftar
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold mt-2 tracking-tight">Pengguna Terdaftar</h1>
                <p class="text-sm text-slate-700 font-semibold mt-1">Siapa saja yang mendaftar sebagai pelamar dan siapa yang mendaftar sebagai pemilik usaha.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-amber-900 font-bold rounded-xl border-2 border-amber-300 hover:bg-amber-50 transition-all text-xs sm:text-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Moderasi
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-12 relative z-20">

    <!-- Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-emerald-200 shadow-sm">
            <span class="text-[10px] font-black text-emerald-700 uppercase tracking-wider">Akun Pelamar</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['pelamar']) }}</div>
            <span class="text-[10px] font-bold text-slate-500">+{{ $stats['pelamar_baru'] }} dalam 7 hari</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-pink-200 shadow-sm">
            <span class="text-[10px] font-black text-pink-700 uppercase tracking-wider">Pemilik Usaha</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['pemilik']) }}</div>
            <span class="text-[10px] font-bold text-slate-500">+{{ $stats['pemilik_baru'] }} dalam 7 hari</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-amber-200 shadow-sm">
            <span class="text-[10px] font-black text-amber-700 uppercase tracking-wider">WA Terverifikasi</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['wa_terverifikasi']) }}</div>
            <span class="text-[10px] font-bold text-slate-500">{{ $stats['wa_belum'] }} pemilik belum verifikasi</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-blue-200 shadow-sm">
            <span class="text-[10px] font-black text-blue-700 uppercase tracking-wider">Kunjungan &amp; Klik</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_views']) }}</div>
            <span class="text-[10px] font-bold text-slate-500">{{ number_format($stats['total_clicks']) }} klik WhatsApp</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-lg border border-pink-200/90">

        <!-- Filter -->
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row gap-2.5 mb-5">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i class="fa-solid fa-magnifying-glass text-sm"></i></span>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama, email, atau nomor HP..."
                    class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
            </div>
            <select name="role" class="px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none font-semibold text-slate-800 text-sm bg-white cursor-pointer">
                <option value="">Semua peran</option>
                <option value="pelamar" {{ $role === 'pelamar' ? 'selected' : '' }}>Pelamar</option>
                <option value="pemilik_usaha" {{ $role === 'pemilik_usaha' ? 'selected' : '' }}>Pemilik Usaha</option>
                <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition-all">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            @if($q !== '' || $role !== '')
                <a href="{{ route('admin.users') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm text-center transition-all">Reset</a>
            @endif
        </form>

        <!-- Desktop -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[10px] uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <th class="py-2.5 pr-3">Pengguna</th>
                        <th class="py-2.5 px-3">Peran</th>
                        <th class="py-2.5 px-3">WhatsApp</th>
                        <th class="py-2.5 px-3">Terdaftar</th>
                        <th class="py-2.5 px-3 text-center">Loker</th>
                        <th class="py-2.5 pl-3 text-center">Tersimpan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-pink-50/40 transition-colors">
                            <td class="py-3 pr-3">
                                <div class="font-extrabold text-slate-900">{{ $user->name }}</div>
                                <div class="text-xs font-semibold text-slate-500">{{ $user->email }}</div>
                            </td>
                            <td class="py-3 px-3">
                                @if($user->role === 'pelamar')
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase">Pelamar</span>
                                @elseif($user->role === 'pemilik_usaha')
                                    <span class="px-2.5 py-1 rounded-lg bg-pink-100 text-pink-800 text-[10px] font-black uppercase">Pemilik Usaha</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-900 text-[10px] font-black uppercase">Admin</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                @if($user->phone)
                                    <div class="font-mono text-xs font-bold text-slate-700">{{ $user->phone }}</div>
                                    @if($user->phone_verified_at)
                                        <span class="text-[10px] font-bold text-emerald-700"><i class="fa-solid fa-circle-check mr-0.5"></i>Terverifikasi</span>
                                    @else
                                        <span class="text-[10px] font-bold text-amber-700"><i class="fa-solid fa-clock mr-0.5"></i>Belum verifikasi</span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 font-semibold">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-xs font-semibold text-slate-600">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-800">{{ $user->job_listings_count }}</td>
                            <td class="py-3 pl-3 text-center font-bold text-slate-800">{{ $user->saved_jobs_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-sm font-semibold text-slate-500">Tidak ada pengguna yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile -->
        <div class="md:hidden space-y-2.5">
            @forelse($users as $user)
                <div class="p-3.5 rounded-xl border border-slate-200">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-extrabold text-slate-900 text-sm truncate">{{ $user->name }}</p>
                            <p class="text-[11px] font-semibold text-slate-500 truncate">{{ $user->email }}</p>
                        </div>
                        @if($user->role === 'pelamar')
                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase shrink-0">Pelamar</span>
                        @elseif($user->role === 'pemilik_usaha')
                            <span class="px-2 py-0.5 rounded-md bg-pink-100 text-pink-800 text-[9px] font-black uppercase shrink-0">Pemilik</span>
                        @else
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-900 text-[9px] font-black uppercase shrink-0">Admin</span>
                        @endif
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] font-semibold text-slate-600">
                        <span><i class="fa-solid fa-calendar mr-1 text-slate-400"></i>{{ $user->created_at->translatedFormat('d M Y') }}</span>
                        @if($user->phone)
                            <span class="font-mono">{{ $user->phone }}</span>
                        @endif
                        <span><i class="fa-solid fa-briefcase mr-1 text-slate-400"></i>{{ $user->job_listings_count }} loker</span>
                        <span><i class="fa-solid fa-heart mr-1 text-slate-400"></i>{{ $user->saved_jobs_count }} tersimpan</span>
                    </div>
                </div>
            @empty
                <p class="py-8 text-center text-sm font-semibold text-slate-500">Tidak ada pengguna yang cocok.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

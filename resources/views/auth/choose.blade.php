@extends('layouts.app')

@section('title', 'Daftar Akun - LokerUMKM Kaltim')

@section('content')
<div class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-14">

    <div class="text-center mb-8">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-100 text-pink-700 text-[11px] font-extrabold uppercase tracking-wider">
            <i class="fa-solid fa-map-location-dot"></i> Khusus Kalimantan Timur
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">Mau daftar sebagai apa?</h1>
        <p class="text-sm text-slate-600 font-medium mt-2 max-w-xl mx-auto">
            Pelamar cukup email & password. Pemilik usaha memakai nomor WhatsApp untuk verifikasi dan kontak lamaran.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Pelamar -->
        <a href="{{ route('register.pelamar') }}" class="group bg-white rounded-2xl border-2 border-slate-200 hover:border-emerald-400 hover:shadow-xl hover:shadow-emerald-500/10 p-6 transition-all">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl mb-4 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-user"></i>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900">Saya Pelamar</h2>
            <p class="text-sm text-slate-600 font-medium mt-1.5">
                Cari kerja di UMKM Kaltim, lihat kontak WhatsApp pemilik usaha, dan simpan loker favorit.
            </p>
            <ul class="mt-4 space-y-1.5 text-xs font-semibold text-slate-700">
                <li><i class="fa-solid fa-check text-emerald-600 mr-1.5"></i>Daftar hanya dengan email & password</li>
                <li><i class="fa-solid fa-check text-emerald-600 mr-1.5"></i>Kontak WA terbuka setelah punya akun</li>
                <li><i class="fa-solid fa-check text-emerald-600 mr-1.5"></i>Gratis, tanpa biaya</li>
            </ul>
            <span class="inline-flex items-center gap-2 mt-5 text-sm font-extrabold text-emerald-700">
                Daftar sebagai pelamar <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </span>
        </a>

        <!-- Pemilik usaha -->
        <a href="{{ route('register.usaha') }}" class="group bg-white rounded-2xl border-2 border-slate-200 hover:border-pink-400 hover:shadow-xl hover:shadow-pink-500/10 p-6 transition-all">
            <div class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center text-xl mb-4 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-store"></i>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900">Saya Pemilik Usaha</h2>
            <p class="text-sm text-slate-600 font-medium mt-1.5">
                Pasang lowongan untuk UMKM Anda dan terima lamaran langsung ke WhatsApp.
            </p>
            <ul class="mt-4 space-y-1.5 text-xs font-semibold text-slate-700">
                <li><i class="fa-solid fa-check text-pink-600 mr-1.5"></i>Wajib nomor WhatsApp aktif</li>
                <li><i class="fa-solid fa-check text-pink-600 mr-1.5"></i>Verifikasi kode via WhatsApp (Fonnte)</li>
                <li><i class="fa-solid fa-check text-pink-600 mr-1.5"></i>Loker tayang setelah disetujui admin</li>
            </ul>
            <span class="inline-flex items-center gap-2 mt-5 text-sm font-extrabold text-pink-700">
                Daftar sebagai pemilik usaha <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </span>
        </a>
    </div>

    <p class="text-center text-sm font-semibold text-slate-600 mt-8">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-pink-700 hover:text-pink-800 font-extrabold">Masuk di sini</a>
    </p>
</div>
@endsection

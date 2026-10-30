@extends('layouts.app')

@section('title', 'Daftar Pemilik Usaha - LokerUMKM Kaltim')

@section('content')
<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-pink-600 mb-4">
                <i class="fa-solid fa-arrow-left"></i> Pilih jenis akun lain
            </a>

            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Daftar Akun Pemilik Usaha</h1>
            <p class="text-xs text-slate-600 font-semibold mt-1">Nomor WhatsApp wajib aktif — dipakai untuk verifikasi dan kontak lamaran.</p>

            @unless($otpReady)
                <div class="mt-4 p-3 rounded-xl bg-amber-50 border border-amber-200 text-[11px] font-semibold text-amber-800">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                    Verifikasi kode WhatsApp otomatis belum aktif (token Fonnte belum diisi). Akun tetap dibuat, admin akan memverifikasi nomor Anda.
                </div>
            @endunless

            <form action="{{ route('register.usaha.post') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Pemilik</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                    @error('name')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="email" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="usaha@email.com"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                        @error('email')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nomor WhatsApp</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="0812xxxxxxx" inputmode="numeric"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                        @error('phone')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" id="password" required minlength="8"
                                class="w-full px-3.5 pr-10 py-2.5 rounded-xl border border-slate-200 focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 text-slate-400 hover:text-slate-600" aria-label="Tampilkan password">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Ulangi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                    </div>
                </div>

                <label class="flex items-start gap-2 text-xs font-semibold text-slate-600">
                    <input type="checkbox" name="terms" value="1" required class="mt-0.5 rounded border-slate-300 text-pink-600 focus:ring-pink-500">
                    <span>Saya menyetujui syarat & ketentuan serta kebijakan privasi LokerUMKM Kaltim.</span>
                </label>
                @error('terms')<p class="text-rose-600 text-xs font-bold">{{ $message }}</p>@enderror

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-extrabold rounded-xl shadow-md shadow-pink-600/25 transition-all text-sm">
                    <i class="fa-solid fa-store mr-1.5"></i> Buat Akun Pemilik Usaha
                </button>
            </form>

            <p class="text-center text-xs font-semibold text-slate-600 mt-4">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-pink-700 font-extrabold hover:text-pink-800">Masuk</a>
            </p>
        </div>

        <aside class="lg:col-span-5 bg-gradient-to-br from-pink-600 to-rose-700 rounded-2xl p-6 text-white shadow-lg">
            <h2 class="text-base font-extrabold">Kenapa pakai WhatsApp?</h2>
            <p class="text-xs text-pink-50 font-medium mt-2 leading-relaxed">
                Lamaran dikirim langsung ke WhatsApp Anda — tanpa perantara, tanpa biaya komisi. Nomor ini juga menjadi identitas usaha Anda di portal.
            </p>
            <ul class="mt-5 space-y-3 text-xs font-semibold">
                <li class="flex gap-2.5"><i class="fa-brands fa-whatsapp text-pink-200 mt-0.5"></i><span>Verifikasi kode 6 digit via WhatsApp</span></li>
                <li class="flex gap-2.5"><i class="fa-solid fa-shield-halved text-pink-200 mt-0.5"></i><span>Mencegah loker palsu & spam</span></li>
                <li class="flex gap-2.5"><i class="fa-solid fa-chart-line text-pink-200 mt-0.5"></i><span>Lihat jumlah kunjungan & klik loker Anda</span></li>
            </ul>
        </aside>
    </div>
</div>
@endsection

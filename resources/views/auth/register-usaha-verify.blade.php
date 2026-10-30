@extends('layouts.app')

@section('title', 'Verifikasi WhatsApp - LokerUMKM Kaltim')

@section('content')
<div class="w-full max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 text-center">

        <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mb-4">
            <i class="fa-brands fa-whatsapp"></i>
        </div>

        <h1 class="text-xl font-extrabold text-slate-900">Verifikasi WhatsApp</h1>
        <p class="text-xs text-slate-600 font-semibold mt-1">
            Masukkan 6 digit kode yang dikirim ke <span class="text-slate-900 font-extrabold">{{ $phone }}</span>
        </p>

        @if(session('success'))
            <div class="mt-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mt-4 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs font-bold text-amber-800">{{ session('error') }}</div>
        @endif

        <form action="{{ route('register.usaha.verify.post') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <input type="text" name="code" id="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required autofocus
                placeholder="000000"
                class="w-full text-center tracking-[0.5em] text-2xl font-extrabold px-3 py-3 rounded-xl border-2 border-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100 text-slate-900 transition-all">
            @error('code')<p class="text-rose-600 text-xs font-bold">{{ $message }}</p>@enderror

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md shadow-emerald-600/25 transition-all text-sm">
                <i class="fa-solid fa-circle-check mr-1.5"></i> Verifikasi & Masuk
            </button>
        </form>

        <form action="{{ route('register.usaha.resend') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="text-xs font-bold text-slate-600 hover:text-emerald-700">
                <i class="fa-solid fa-rotate-right mr-1"></i> Kirim ulang kode
            </button>
        </form>

        <p class="text-[11px] text-slate-500 font-medium mt-5">
            Kode berlaku 5 menit. Bila tidak menerima kode, periksa nomor atau hubungi admin.
        </p>
    </div>
</div>
@endsection

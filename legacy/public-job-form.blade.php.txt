@extends('layouts.app')

@section('title', 'Pasang Lowongan Kerja UMKM - Formulir Pendaftaran')

@section('content')

<div class="hero-pink-gradient text-slate-900 py-14 border-b border-pink-200/50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="px-4 py-1.5 bg-white/80 text-pink-700 text-xs sm:text-sm font-black uppercase tracking-wider rounded-full border-2 border-pink-300 shadow-sm">
            FASE 1: Pendaftaran Lowongan Kerja
        </span>
        <h1 class="text-3xl sm:text-5xl font-black mt-4 tracking-tight">Pasang Lowongan Kerja UMKM Anda</h1>
        <p class="mt-3 text-sm sm:text-base text-slate-700 font-semibold max-w-xl mx-auto">
            Jangkau calon pekerja di kota Anda secara cepat. Lowongan akan diverifikasi oleh Admin sebelum resmi tayang.
        </p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-24 relative z-20">
    <div class="bg-white rounded-3xl p-7 sm:p-11 shadow-xl border-2 border-pink-200/90">
        
        <!-- Info Banner FASE 1 & FASE 2 -->
        <div class="mb-9 p-5 bg-pink-50 border-2 border-pink-200 rounded-2xl flex items-start gap-4 text-pink-950 text-xs sm:text-sm font-semibold">
            <div class="w-9 h-9 rounded-2xl bg-pink-500 text-white flex items-center justify-center shrink-0 mt-0.5 font-bold shadow-md shadow-pink-500/20">
                <i class="fa-solid fa-info text-base"></i>
            </div>
            <div class="leading-relaxed">
                <strong class="font-black block text-pink-900 text-sm mb-0.5">Alur Publikasi Loker UMKM:</strong>
                Setelah menekan <strong>"Kirim Lowongan"</strong>, sistem menyimpan data dengan status <span class="bg-amber-100 text-amber-950 font-black px-2 py-0.5 rounded border border-amber-300">Pending</span>. Tim Admin akan melakukan moderasi sebelum tayang di Halaman Utama.
            </div>
        </div>

        <form action="{{ route('jobs.store') }}" method="POST" class="space-y-7">
            @csrf

            <!-- Section 1: Informasi Usaha & Posisi -->
            <div class="space-y-4">
                <h3 class="text-base font-black text-slate-900 border-b border-pink-100 pb-2.5 flex items-center gap-2.5">
                    <i class="fa-solid fa-store text-pink-500"></i> Detail Usaha & Posisi Loker
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Usaha -->
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                            Nama Usaha / Toko / UMKM <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" required placeholder="Contoh: Kedai Kopi Sejahtera" class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">
                    </div>

                    <!-- Posisi Pekerjaan -->
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                            Posisi Pekerjaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="posisi" value="{{ old('posisi') }}" required placeholder="Contoh: Barista / Staff Kasir" class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2: Kategori, Lokasi & Tipe -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Kategori Loker <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" required class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Pekerjaan -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Tipe Pekerjaan <span class="text-rose-500">*</span>
                    </label>
                    <select name="tipe_pekerjaan" required class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">
                        <option value="Full Time" {{ old('tipe_pekerjaan') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                        <option value="Part Time" {{ old('tipe_pekerjaan') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                        <option value="Freelance" {{ old('tipe_pekerjaan') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="Magang / Internship" {{ old('tipe_pekerjaan') == 'Magang / Internship' ? 'selected' : '' }}>Magang / Internship</option>
                    </select>
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Lokasi Kota/Area <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" required placeholder="Contoh: Bandung Jawa Barat" class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">
                </div>
            </div>

            <!-- Section 3: Gaji & WhatsApp Contact -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Estimasi Gaji -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Estimasi Gaji (Opsional)
                    </label>
                    <input type="text" name="gaji" value="{{ old('gaji') }}" placeholder="Contoh: Rp 2.500.000 / bulan" class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">
                </div>

                <!-- Nomor WA -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Nomor WhatsApp <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-600 font-bold text-base">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <input type="text" name="nomor_wa" value="{{ old('nomor_wa') }}" required placeholder="081234567890" class="w-full pl-11 pr-4 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">
                    </div>
                    <span class="text-xs text-slate-500 font-semibold mt-1.5 block">Pelamar melamar langsung ke nomor WA ini.</span>
                </div>
            </div>

            <!-- Section 4: Deskripsi & Syarat -->
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Deskripsi Pekerjaan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="deskripsi" rows="4" required placeholder="Jelaskan aktivitas harian, jam kerja, dan tanggung jawab posisi ini..." class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Persyaratan Pelamar (Opsional)
                    </label>
                    <textarea name="syarat" rows="4" placeholder="Contoh: 1. Pria/Wanita max 25th, 2. Pendidikan SMA/K, 3. Jujur dan komunikatif" class="w-full px-4.5 py-4 bg-pink-50/40 border-2 border-pink-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-4 focus:ring-pink-300 focus:border-pink-500 outline-none transition-all">{{ old('syarat') }}</textarea>
                </div>
            </div>

            <!-- Submit Button (FASE 1) -->
            <div class="pt-5 border-t border-pink-100 flex items-center justify-end gap-3.5">
                <a href="{{ route('home') }}" class="px-6 py-3.5 rounded-2xl border-2 border-pink-200 text-slate-700 text-xs sm:text-sm font-bold hover:bg-pink-100/50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-9 py-4 bg-gradient-to-r from-pink-500 via-rose-500 to-pink-600 hover:from-pink-600 hover:to-rose-600 text-white font-black text-sm sm:text-base rounded-2xl shadow-xl shadow-pink-500/30 transition-all flex items-center gap-2.5">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Lowongan</span>
                </button>
            </div>

        </form>

    </div>
</div>

@endsection

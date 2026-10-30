@extends('layouts.app')

@section('title', 'Pasang Loker - ' . ($profile?->nama_usaha ?? 'LokerUMKM'))

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-6">
        <a href="{{ route('business.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-pink-600 transition-colors mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-pink-500 to-rose-500 flex items-center justify-center shadow-md shadow-pink-500/30">
                <i class="fa-solid fa-plus-circle text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">Pasang Loker Baru</h1>
                <p class="text-xs text-slate-500 font-semibold">Data usaha otomatis terisi dari profil Anda</p>
            </div>
        </div>
    </div>

    @if($profile && $profile->profil_lengkap)
        <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-check text-white text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-emerald-800 text-xs">Data usaha sudah terisi otomatis</p>
                <p class="text-emerald-700 font-medium text-[11px]">
                    Atas nama <strong>{{ $profile->nama_usaha }}</strong> · kontak WA <strong>{{ $profile->no_hp }}</strong>
                </p>
            </div>
            <a href="{{ route('business.profile.edit') }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-800 whitespace-nowrap">
                <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
            </a>
        </div>
    @endif

    <form action="{{ route('business.jobs.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data" x-data="{ isPremium: {{ old('is_premium') ? 'true' : 'false' }} }">
        @csrf

        {{-- Info Posisi & Kategori --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-4 pb-2.5 border-b border-pink-100">
                <i class="fa-solid fa-briefcase text-pink-500 mr-2"></i>Informasi Posisi
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="posisi" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Posisi / Jabatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="posisi" id="posisi" value="{{ old('posisi') }}" required
                        placeholder="Contoh: Kasir, Chef, Admin Social Media, Driver..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                    @error('posisi')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="category_id" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tipe_pekerjaan" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Tipe Pekerjaan <span class="text-rose-500">*</span>
                    </label>
                    <select name="tipe_pekerjaan" id="tipe_pekerjaan" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all bg-white">
                        @foreach(['Full Time', 'Part Time', 'Freelance', 'Magang'] as $tipe)
                            <option value="{{ $tipe }}" {{ old('tipe_pekerjaan') === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="lokasi" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Lokasi Kerja <span class="text-rose-500">*</span>
                    </label>
                    <select name="lokasi" id="lokasi" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all bg-white cursor-pointer">
                        <option value="">Pilih kota/kabupaten</option>
                        @foreach(config('wilayah.kota') as $kota)
                            <option value="{{ $kota }}" {{ old('lokasi') === $kota ? 'selected' : '' }}>{{ $kota }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-500 font-semibold mt-1">Hanya kota/kabupaten di Kalimantan Timur.</p>
                    @error('lokasi')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="gaji" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Gaji / Upah (opsional)</label>
                    <input type="text" name="gaji" id="gaji" value="{{ old('gaji') }}"
                        placeholder="Rp 2.000.000 / Sesuai UMR / Nego"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                </div>
            </div>
        </div>

        {{-- Premium --}}
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-5 border border-amber-200 shadow-sm">
            <h2 class="text-sm font-extrabold text-amber-900 mb-4 pb-2.5 border-b border-amber-200 flex items-center">
                <i class="fa-solid fa-crown text-amber-500 mr-2"></i> Ajukan Loker Premium?
            </h2>

            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="is_premium" value="1" x-model="isPremium" {{ old('is_premium') ? 'checked' : '' }}
                    class="w-4 h-4 mt-0.5 rounded border-amber-400 text-amber-500 focus:ring-amber-500">
                <div>
                    <span class="block font-bold text-amber-900 text-sm">Ya, ajukan Premium (Rp 50.000)</span>
                    <p class="text-xs font-medium text-amber-700 mt-0.5">
                        Loker premium tampil paling atas dan diberi badge khusus. Status premium baru aktif
                        setelah admin memverifikasi bukti pembayaran Anda.
                    </p>
                </div>
            </label>

            <div x-show="isPremium" x-cloak class="mt-4 pt-4 border-t border-amber-200">
                <div class="bg-white p-3.5 rounded-xl border border-amber-100 text-xs font-medium text-amber-800 mb-3">
                    Transfer ke <strong>BCA 1234567890 a.n. Info Loker UMKM</strong> sebesar <strong>Rp 50.000</strong>,
                    lalu unggah bukti transfer di bawah ini.
                </div>

                <label for="bukti_pembayaran" class="block text-[11px] font-bold text-amber-900 mb-1.5 uppercase tracking-wide">
                    Upload Bukti Pembayaran <span class="text-rose-500">*</span>
                </label>
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/jpeg,image/png,image/webp" :required="isPremium"
                    class="w-full px-3.5 py-2.5 bg-white rounded-xl border border-amber-200 focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100 font-semibold text-slate-800 text-sm transition-all">
                <p class="text-[11px] text-amber-700 mt-1.5 font-medium">Format JPG/PNG/WEBP, maksimal 4MB.</p>
                @error('bukti_pembayaran')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-4 pb-2.5 border-b border-pink-100">
                <i class="fa-solid fa-file-lines text-pink-500 mr-2"></i>Detail Pekerjaan
            </h2>
            <div class="space-y-4">
                <div>
                    <label for="deskripsi" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Deskripsi Pekerjaan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" required
                        placeholder="Jelaskan tugas dan tanggung jawab pekerjaan ini...&#10;Contoh:&#10;- Melayani pelanggan di kasir&#10;- Mengelola transaksi penjualan"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all resize-y">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="syarat" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Syarat &amp; Kualifikasi <span class="text-slate-400 font-medium normal-case">(opsional)</span>
                    </label>
                    <textarea name="syarat" id="syarat" rows="4"
                        placeholder="Contoh:&#10;- Min. SMA/SMK sederajat&#10;- Domisili dekat lokasi usaha"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all resize-y">{{ old('syarat') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Auto-filled --}}
        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
            <p class="text-[11px] font-bold text-slate-600 mb-1.5">
                <i class="fa-solid fa-robot text-pink-500 mr-1"></i>Diisi otomatis dari profil usaha:
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 text-[11px] font-semibold text-slate-500">
                <div><i class="fa-solid fa-store text-pink-400 mr-1"></i> <strong>Nama Usaha:</strong> {{ $profile?->nama_usaha ?? '—' }}</div>
                <div><i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i> <strong>Kontak WA:</strong> {{ $profile?->no_hp ?? '—' }}</div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">
            <button type="submit" id="btn-post-job" class="flex-1 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-bold rounded-xl hover:from-pink-700 hover:to-rose-700 shadow-md shadow-pink-500/25 transition-all text-sm">
                <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim untuk Ditinjau Admin
            </button>
            <a href="{{ route('business.dashboard') }}" class="px-5 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all text-sm text-center">
                Batal
            </a>
        </div>

        <p class="text-center text-[11px] font-medium text-slate-400">
            <i class="fa-solid fa-shield-halved text-pink-400 mr-1"></i>
            Loker ditinjau admin sebelum tayang, biasanya 1×24 jam.
        </p>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Profil Usaha - LokerUMKM')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-6">
        <a href="{{ route('business.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-pink-600 transition-colors mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-pink-500 to-rose-500 flex items-center justify-center shadow-md shadow-pink-500/30">
                <i class="fa-solid fa-store text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">Profil Usaha</h1>
                <p class="text-xs text-slate-500 font-semibold">Diisi sekali, dipakai otomatis saat posting loker</p>
            </div>
        </div>
    </div>

    @if(!$profile->profil_lengkap || !$profile->exists)
        <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 rounded-xl">
            <p class="text-blue-800 font-semibold text-xs flex items-start gap-2">
                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 shrink-0"></i>
                Lengkapi profil Anda. Setelah terisi, nama usaha &amp; nomor WA akan otomatis dipakai saat memasang loker berikutnya.
            </p>
        </div>
    @endif

    <form action="{{ route('business.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Data Pemilik --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-4 pb-2.5 border-b border-pink-100">
                <i class="fa-solid fa-user text-pink-500 mr-2"></i>Data Pemilik
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="nama_pemilik" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Nama Pemilik <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik', $profile->nama_pemilik) }}" required
                        placeholder="Nama lengkap pemilik usaha"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                    @error('nama_pemilik')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Data Usaha --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-4 pb-2.5 border-b border-pink-100">
                <i class="fa-solid fa-store text-pink-500 mr-2"></i>Data Usaha
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nama_usaha" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Nama Usaha <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_usaha" id="nama_usaha" value="{{ old('nama_usaha', $profile->nama_usaha) }}" required
                        placeholder="Nama usaha/toko"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                    @error('nama_usaha')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="no_hp" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Nomor HP / WA <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $profile->no_hp) }}" required
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                    <p class="text-[11px] text-slate-400 font-medium mt-1">Dipakai untuk redirect WA pelamar</p>
                    @error('no_hp')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="kategori_usaha" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kategori Usaha</label>
                    <select name="kategori_usaha" id="kategori_usaha"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Kuliner & F&B', 'Fashion & Clothing', 'Kerajinan Tangan', 'Teknologi & Digital', 'Jasa & Layanan', 'Retail & Toko', 'Pertanian & Peternakan', 'Pendidikan & Pelatihan', 'Kesehatan & Kecantikan', 'Lainnya'] as $kat)
                            <option value="{{ $kat }}" {{ old('kategori_usaha', $profile->kategori_usaha) === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="website" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Website (opsional)</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $profile->website) }}"
                        placeholder="https://usahaanda.com"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                    @error('website')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat_usaha" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Alamat Usaha</label>
                    <input type="text" name="alamat_usaha" id="alamat_usaha" value="{{ old('alamat_usaha', $profile->alamat_usaha) }}"
                        placeholder="Jl. Contoh No.1, Kota"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                </div>
                <div class="sm:col-span-2">
                    <label for="deskripsi_usaha" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Deskripsi Usaha (opsional)</label>
                    <textarea name="deskripsi_usaha" id="deskripsi_usaha" rows="3"
                        placeholder="Ceritakan singkat tentang usaha Anda..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all resize-y">{{ old('deskripsi_usaha', $profile->deskripsi_usaha) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Media Sosial --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-4 pb-2.5 border-b border-pink-100">
                <i class="fa-solid fa-share-nodes text-pink-500 mr-2"></i>Media Sosial Aktif
                <span class="text-slate-400 font-medium text-xs normal-case">(opsional)</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="medsos_instagram" class="block text-[11px] font-bold text-slate-700 mb-1.5">
                        <i class="fa-brands fa-instagram text-pink-500 mr-1"></i>Instagram
                    </label>
                    <input type="text" name="medsos_instagram" id="medsos_instagram" value="{{ old('medsos_instagram', $profile->medsos_instagram) }}"
                        placeholder="@namaakun"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                </div>
                <div>
                    <label for="medsos_tiktok" class="block text-[11px] font-bold text-slate-700 mb-1.5">
                        <i class="fa-brands fa-tiktok text-slate-700 mr-1"></i>TikTok
                    </label>
                    <input type="text" name="medsos_tiktok" id="medsos_tiktok" value="{{ old('medsos_tiktok', $profile->medsos_tiktok) }}"
                        placeholder="@namaakun"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                </div>
                <div>
                    <label for="medsos_facebook" class="block text-[11px] font-bold text-slate-700 mb-1.5">
                        <i class="fa-brands fa-facebook text-blue-600 mr-1"></i>Facebook
                    </label>
                    <input type="text" name="medsos_facebook" id="medsos_facebook" value="{{ old('medsos_facebook', $profile->medsos_facebook) }}"
                        placeholder="Nama halaman FB"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-100 font-semibold text-slate-800 text-sm transition-all">
                </div>
            </div>
        </div>

        {{-- Verifikasi Identitas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-1 pb-2.5 border-b border-pink-100">
                <i class="fa-solid fa-id-card text-pink-500 mr-2"></i>Verifikasi Identitas
                <span class="text-slate-400 font-medium text-xs normal-case">(opsional, mempercepat verifikasi)</span>
            </h2>
            <p class="text-xs font-medium text-slate-500 mt-2 mb-4">
                Unggah <strong>foto selfie sambil memegang KTP</strong> — wajah dan data KTP harus terlihat jelas.
                Format JPG/PNG/WEBP, maks 4MB.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Selfie + KTP --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Foto Selfie + KTP</label>

                    @if($profile->foto_selfie_ktp)
                        <div class="mb-3 relative">
                            <img src="{{ $profile->foto_selfie_ktp_url }}" alt="Foto selfie bersama KTP" class="w-full h-32 object-cover rounded-xl border-2 border-emerald-300">
                            <span class="absolute top-2 right-2 px-2 py-0.5 bg-emerald-500 text-white text-[10px] font-bold rounded-md">
                                <i class="fa-solid fa-check"></i> Terunggah
                            </span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-400 mb-2">Unggah ulang untuk mengganti:</p>
                    @endif

                    <label for="foto_selfie_ktp" class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-pink-300 rounded-xl cursor-pointer hover:bg-pink-50 transition-colors">
                        <div class="text-center px-2">
                            <i class="fa-solid fa-camera-retro text-pink-400 text-xl mb-1"></i>
                            <p class="text-[11px] font-bold text-slate-600">Pilih foto selfie + KTP</p>
                            <p class="text-[10px] text-slate-400 font-medium">JPG, PNG, WEBP · maks 4MB</p>
                        </div>
                        <input type="file" name="foto_selfie_ktp" id="foto_selfie_ktp" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </label>
                    <p class="text-[11px] text-slate-400 font-medium mt-1.5" id="selfie-filename"></p>
                    @error('foto_selfie_ktp')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- SIM --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wide">Foto SIM (opsional)</label>

                    @if($profile->foto_sim)
                        <div class="mb-3 relative">
                            <img src="{{ $profile->foto_sim_url }}" alt="Foto SIM" class="w-full h-32 object-cover rounded-xl border-2 border-emerald-300">
                            <span class="absolute top-2 right-2 px-2 py-0.5 bg-emerald-500 text-white text-[10px] font-bold rounded-md">
                                <i class="fa-solid fa-check"></i> Terunggah
                            </span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-400 mb-2">Unggah ulang untuk mengganti:</p>
                    @endif

                    <label for="foto_sim" class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-pink-300 rounded-xl cursor-pointer hover:bg-pink-50 transition-colors">
                        <div class="text-center px-2">
                            <i class="fa-solid fa-id-card text-pink-400 text-xl mb-1"></i>
                            <p class="text-[11px] font-bold text-slate-600">Pilih foto SIM</p>
                            <p class="text-[10px] text-slate-400 font-medium">JPG, PNG, WEBP · maks 4MB</p>
                        </div>
                        <input type="file" name="foto_sim" id="foto_sim" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </label>
                    <p class="text-[11px] text-slate-400 font-medium mt-1.5" id="sim-filename"></p>
                    @error('foto_sim')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">
            <button type="submit" id="btn-save-profile" class="flex-1 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-bold rounded-xl hover:from-pink-700 hover:to-rose-700 shadow-md shadow-pink-500/25 transition-all text-sm">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Profil Usaha
            </button>
            <a href="{{ route('business.dashboard') }}" class="px-5 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all text-sm text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    function bindFilePreview(inputId, labelId) {
        const input = document.getElementById(inputId);
        const label = document.getElementById(labelId);
        if (!input || !label) return;
        input.addEventListener('change', function () {
            label.textContent = this.files[0]?.name ? '📎 ' + this.files[0].name : '';
        });
    }

    bindFilePreview('foto_selfie_ktp', 'selfie-filename');
    bindFilePreview('foto_sim', 'sim-filename');
</script>
@endsection

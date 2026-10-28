@extends('layouts.app')

@section('title', 'Profil Pelamar - LokerUMKM')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-6">
        <a href="{{ route('pelamar.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-500/30">
                <i class="fa-solid fa-user text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">Profil Pelamar</h1>
                <p class="text-xs text-slate-500 font-semibold">Bantu pemilik usaha mengenal Anda lebih cepat</p>
            </div>
        </div>
    </div>

    <form action="{{ route('pelamar.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-4 pb-2.5 border-b border-emerald-100">
                <i class="fa-solid fa-id-badge text-emerald-600 mr-2"></i>Ringkasan Diri
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="headline" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Headline
                    </label>
                    <input type="text" name="headline" id="headline" value="{{ old('headline', $profile->headline) }}"
                        placeholder="Contoh: Barista berpengalaman 2 tahun"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100 font-semibold text-slate-800 text-sm transition-all">
                    @error('headline')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="city" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kota Domisili</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $profile->city) }}"
                        placeholder="Contoh: Bandung"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100 font-semibold text-slate-800 text-sm transition-all">
                    @error('city')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="skills" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Keahlian / Pengalaman</label>
                    <textarea name="skills" id="skills" rows="4"
                        placeholder="Contoh:&#10;- Melayani pelanggan & membuat kopi manual brew&#10;- Menangani kasir dan stok harian"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100 font-semibold text-slate-800 text-sm transition-all resize-y">{{ old('skills', $profile->skills) }}</textarea>
                    @error('skills')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900 mb-1 pb-2.5 border-b border-emerald-100">
                <i class="fa-solid fa-file-pdf text-emerald-600 mr-2"></i>CV (PDF)
            </h2>
            <p class="text-xs font-medium text-slate-500 mt-2 mb-4">Opsional. Format PDF, maksimal 5MB. Hanya Anda dan admin yang bisa mengunduhnya.</p>

            @if($profile->cv_path)
                <div class="mb-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between gap-3">
                    <span class="text-xs font-bold text-emerald-800 truncate"><i class="fa-solid fa-file-lines mr-1"></i>CV sudah terunggah</span>
                    <a href="{{ route('documents.show', ['path' => $profile->cv_path]) }}" target="_blank" rel="noopener"
                       class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 whitespace-nowrap">
                        <i class="fa-solid fa-download mr-1"></i>Buka
                    </a>
                </div>
            @endif

            <label for="cv" class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-emerald-300 rounded-xl cursor-pointer hover:bg-emerald-50 transition-colors">
                <div class="text-center px-2">
                    <i class="fa-solid fa-cloud-arrow-up text-emerald-500 text-xl mb-1"></i>
                    <p class="text-[11px] font-bold text-slate-600">Pilih file CV (PDF)</p>
                    <p class="text-[10px] text-slate-400 font-medium">Maks 5MB</p>
                </div>
                <input type="file" name="cv" id="cv" accept="application/pdf" class="hidden">
            </label>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5" id="cv-filename"></p>
            @error('cv')<p class="text-rose-600 text-xs font-bold mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">
            <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-600/25 transition-all text-sm">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Profil
            </button>
            <a href="{{ route('pelamar.dashboard') }}" class="px-5 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all text-sm text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('cv')?.addEventListener('change', function () {
        document.getElementById('cv-filename').textContent = this.files[0]?.name ? '📎 ' + this.files[0].name : '';
    });
</script>
@endsection

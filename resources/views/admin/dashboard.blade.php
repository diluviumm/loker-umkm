@extends('layouts.app')

@section('title', 'Dashboard Moderasi Admin - Info Loker UMKM')

@section('content')

<div class="hero-pink-gradient text-slate-900 py-8 border-b border-pink-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-4 py-1.5 bg-white/80 text-amber-900 text-xs font-black uppercase tracking-wider rounded-full border-2 border-amber-300 shadow-sm">
                    Verifikasi & Moderasi Admin
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold mt-2 tracking-tight">Dashboard Verifikasi Lowongan Kerja</h1>
                <p class="text-sm text-slate-700 font-semibold mt-1">Kelola permohonan loker pending, setujui status Gratis/Premium, atau tolak loker tidak layak.</p>
            </div>
            
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-10 relative z-20">
    
    <!-- Top Stat Cards Large -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-pink-200 shadow-sm text-center">
            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Data</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</div>
        </div>

        <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="bg-amber-50 p-4 rounded-2xl border border-amber-300 shadow-sm text-center hover:bg-amber-100/80 transition-all {{ $statusFilter == 'pending' ? 'ring-4 ring-amber-400' : '' }}">
            <span class="text-xs font-black text-amber-800 uppercase tracking-wider">Loker Pending</span>
            <div class="text-2xl font-extrabold text-amber-900 mt-1">{{ $stats['pending'] }}</div>
        </a>

        <a href="{{ route('admin.dashboard', ['status' => 'aktif']) }}" class="bg-emerald-50 p-4 rounded-2xl border border-emerald-300 shadow-sm text-center hover:bg-emerald-100/80 transition-all {{ $statusFilter == 'aktif' ? 'ring-4 ring-emerald-400' : '' }}">
            <span class="text-xs font-black text-emerald-800 uppercase tracking-wider">Loker Aktif</span>
            <div class="text-2xl font-extrabold text-emerald-950 mt-1">{{ $stats['aktif'] }}</div>
        </a>

        <div class="bg-yellow-50 p-4 rounded-2xl border border-yellow-300 shadow-sm text-center">
            <span class="text-xs font-black text-yellow-900 uppercase tracking-wider">Fitur Premium</span>
            <div class="text-2xl font-extrabold text-yellow-950 mt-1">{{ $stats['premium'] }}</div>
        </div>

        <a href="{{ route('admin.dashboard', ['status' => 'ditolak']) }}" class="bg-rose-50 p-4 rounded-2xl border border-rose-300 shadow-sm text-center hover:bg-rose-100/80 transition-all {{ $statusFilter == 'ditolak' ? 'ring-4 ring-rose-400' : '' }}">
            <span class="text-xs font-black text-rose-800 uppercase tracking-wider">Ditolak</span>
            <div class="text-2xl font-extrabold text-rose-950 mt-1">{{ $stats['ditolak'] }}</div>
        </a>

        <a href="{{ route('admin.dashboard', ['status' => 'kadaluarsa']) }}" class="bg-slate-100 p-4 rounded-2xl border border-slate-300 shadow-sm text-center hover:bg-slate-200 transition-all {{ $statusFilter == 'kadaluarsa' ? 'ring-4 ring-slate-500' : '' }}">
            <span class="text-xs font-black text-slate-600 uppercase tracking-wider">Kadaluarsa</span>
            <div class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['kadaluarsa'] }}</div>
        </a>
    </div>

    <!-- Status Tabs Nav Large -->
    <div class="bg-white rounded-2xl p-5 shadow-lg border border-pink-200/90">
        
        <div class="flex items-center justify-between border-b border-pink-100 pb-5 mb-7">
            <div class="flex items-center gap-3 overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.dashboard', ['status' => 'semua']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ ! in_array($statusFilter, ['pending','aktif','ditolak','kadaluarsa'], true) ? 'bg-slate-900 text-white shadow-md' : 'bg-pink-50 text-slate-700 hover:bg-pink-100 border border-pink-200/60' }}">
                    <i class="fa-solid fa-list mr-1.5"></i> Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-black whitespace-nowrap transition-all {{ $statusFilter == 'pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'bg-pink-50 text-slate-700 hover:bg-pink-100 border border-pink-200/60' }}">
                    <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Loker Pending ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'aktif']) }}" class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-black whitespace-nowrap transition-all {{ $statusFilter == 'aktif' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'bg-pink-50 text-slate-700 hover:bg-pink-100 border border-pink-200/60' }}">
                    <i class="fa-solid fa-circle-check mr-1.5"></i> Loker Aktif ({{ $stats['aktif'] }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'ditolak']) }}" class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-black whitespace-nowrap transition-all {{ $statusFilter == 'ditolak' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-pink-50 text-slate-700 hover:bg-pink-100 border border-pink-200/60' }}">
                    <i class="fa-solid fa-circle-xmark mr-1.5"></i> Ditolak ({{ $stats['ditolak'] }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'kadaluarsa']) }}" class="px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-black whitespace-nowrap transition-all {{ $statusFilter == 'kadaluarsa' ? 'bg-slate-900 text-white shadow-lg' : 'bg-pink-50 text-slate-700 hover:bg-pink-100 border border-pink-200/60' }}">
                    <i class="fa-solid fa-calendar-xmark mr-1.5"></i> Kadaluarsa ({{ $stats['kadaluarsa'] }})
                </a>
            </div>

            <span class="text-xs text-pink-600 font-extrabold hidden lg:inline-block">Moderasi Loker</span>
        </div>

        @if($jobs->isEmpty())
            <div class="text-center py-14 text-slate-400">
                <i class="fa-solid fa-inbox text-4xl mb-3 text-pink-300"></i>
                <p class="text-sm font-extrabold">Tidak ada loker pada status '{{ strtoupper($statusFilter) }}'.</p>
            </div>
        @else
            <!-- Job List Moderation Cards Large -->
            <div class="space-y-5">
                @foreach($jobs as $job)
                    @php
                        $ownerProfile = $job->user?->businessProfile;
                    @endphp
                    <div x-data="{ showModal: false }" class="p-5 rounded-2xl border-2 transition-all flex flex-col lg:flex-row lg:items-center justify-between gap-4 {{ $job->status == 'pending' ? 'bg-amber-50/60 border-amber-200' : ($job->status == 'aktif' ? 'bg-white border-pink-200 hover:border-pink-300' : 'bg-slate-50 border-slate-200') }}">
                        
                        <!-- Left Details Large -->
                        <div class="space-y-2 max-w-2xl">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <!-- Status Badge -->
                                @if($job->status == 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-amber-200 text-amber-950 border border-amber-300">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Pending Moderasi
                                    </span>
                                @elseif($job->status == 'aktif')
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-emerald-100 text-emerald-900 border border-emerald-300">
                                        <i class="fa-solid fa-check mr-1"></i> Aktif Tayang
                                    </span>
                                @elseif($job->status == 'ditolak')
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-rose-100 text-rose-900 border border-rose-300">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @elseif($job->status == 'kadaluarsa')
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-slate-200 text-slate-800 border border-slate-300">
                                        <i class="fa-solid fa-calendar-xmark mr-1"></i> Kadaluarsa (>30 Hari)
                                    </span>
                                @endif

                                <!-- Premium Badge -->
                                @if($job->is_premium)
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-yellow-400 text-slate-950 shadow-sm">
                                        <i class="fa-solid fa-crown mr-1"></i> Premium ⭐
                                    </span>
                                @endif

                                <span class="text-xs text-slate-500 font-semibold">Dikirim {{ $job->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>

                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                                {{ $job->posisi }} <span class="text-pink-700 font-bold text-base">at {{ $job->nama_usaha }}</span>
                            </h3>

                            <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-slate-700 font-bold pt-1">
                                <span><i class="fa-solid {{ $job->category->icon ?? 'fa-briefcase' }} text-pink-500 mr-1.5"></i>{{ $job->category->nama ?? 'Umum' }}</span>
                                <span><i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i>{{ $job->lokasi }}</span>
                                <span><i class="fa-brands fa-whatsapp text-emerald-600 mr-1.5"></i>{{ $job->nomor_wa }}</span>
                                @if($job->gaji)
                                    <span><i class="fa-solid fa-money-bill-wave text-emerald-600 mr-1.5"></i>{{ $job->gaji }}</span>
                                @endif
                            </div>

                            <div class="mt-2 text-xs sm:text-sm text-slate-600 line-clamp-2 bg-pink-50/50 p-3 rounded-2xl border border-pink-100 font-medium">
                                {{ $job->deskripsi }}
                            </div>

                            <!-- Tombol Cek Profil Pemilik -->
                            <div class="pt-2">
                                <button @click="showModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black rounded-2xl text-xs shadow-md transition-all transform hover:-translate-y-0.5">
                                    <i class="fa-solid fa-id-card text-sm"></i>
                                    <span>🔍 Cek Data & Dokumen Pemilik Usaha</span>
                                    @if($ownerProfile?->foto_selfie_ktp || $ownerProfile?->foto_sim)
                                        <span class="px-2 py-0.5 bg-emerald-400 text-slate-950 font-bold rounded-full text-[10px]">
                                            <i class="fa-solid fa-file-circle-check"></i> Selfie+KTP / SIM Ada
                                        </span>
                                    @endif
                                </button>
                            </div>
                        </div>

                        <!-- Right Actions Large (FASE 2 Decisions) -->
                        <div class="flex flex-wrap lg:flex-col items-stretch gap-2.5 shrink-0 border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-200">
                            
                            @if($job->status == 'pending')
                                @php $paidPremium = $job->hasPaidPremium(); @endphp

                                <div class="text-[11px] font-bold text-slate-600 mb-1 text-center lg:text-right">Keputusan Admin:</div>

                                {{-- Revisi PR Premium: hanya satu jalur yang aktif sesuai status pembayaran --}}
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="is_premium" value="0">
                                        <button type="submit" @disabled($paidPremium)
                                            title="{{ $paidPremium ? 'Sudah dibayar — persetujuan gratis dinonaktifkan' : 'Setujui sebagai loker gratis' }}"
                                            class="px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ $paidPremium ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm' }}">
                                            <i class="fa-solid {{ $paidPremium ? 'fa-check' : 'fa-lock' }} mr-1"></i> Setujui Gratis
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="is_premium" value="1">
                                        <button type="submit" @disabled(! $paidPremium)
                                            title="{{ $paidPremium ? 'Bukti pembayaran premium terverifikasi' : 'Belum ada bukti pembayaran — persetujuan premium dinonaktifkan' }}"
                                            class="px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ $paidPremium ? 'bg-amber-500 hover:bg-amber-600 text-slate-950 shadow-sm' : 'bg-slate-200 text-slate-400 cursor-not-allowed' }}">
                                            <i class="fa-solid {{ $paidPremium ? 'fa-crown' : 'fa-lock' }} mr-1"></i> Setujui Premium
                                        </button>
                                    </form>
                                </div>

                                <p class="text-[10px] font-semibold text-center lg:text-right {{ $paidPremium ? 'text-emerald-700' : 'text-amber-700' }}">
                                    <i class="fa-solid {{ $paidPremium ? 'fa-circle-check' : 'fa-circle-info' }} mr-1"></i>
                                    {{ $paidPremium ? 'Pembayaran premium terverifikasi' : 'Belum ada bukti pembayaran premium' }}
                                </p>

                                <form action="{{ route('admin.jobs.reject', $job->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENOLAK loker ini?')" class="w-full px-3.5 py-2 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border border-rose-300 transition-colors">
                                        <i class="fa-solid fa-xmark mr-1"></i> Tolak Loker
                                    </button>
                                </form>
                            @else
                                <!-- Options for Active / Expired / Rejected Jobs -->
                                <div class="flex items-center gap-2.5">
                                    <!-- Toggle Premium -->
                                    <form action="{{ route('admin.jobs.toggle-premium', $job->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 text-xs font-black rounded-2xl border transition-colors {{ $job->is_premium ? 'bg-amber-100 border-amber-300 text-amber-950' : 'bg-pink-50 border-pink-200 text-pink-900 hover:bg-pink-100' }}">
                                            <i class="fa-solid fa-crown mr-1.5 text-amber-500"></i> {{ $job->is_premium ? 'Unset Premium' : 'Set Premium ⭐' }}
                                        </button>
                                    </form>

                                    @if($job->status != 'aktif')
                                        <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-2xl">
                                                Aktifkan Loker
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete -->
                                    <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus permanen loker ini dari database?')" class="px-3.5 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 text-xs font-bold rounded-2xl">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>

                        <!-- MODAL DATA PEMILIK USAHA -->
                        <div x-cloak x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" x-transition>
                            <div @click.away="showModal = false" class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden border-2 border-pink-200 max-h-[90vh] flex flex-col">
                                
                                <!-- Header Modal -->
                                <div class="p-6 bg-gradient-to-r from-pink-600 to-rose-600 text-white flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center font-black">
                                            <i class="fa-solid fa-shield-halved text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-black leading-tight">Verifikasi Identitas Pemilik Usaha</h3>
                                            <p class="text-xs text-pink-100 font-semibold">Gunakan data ini untuk menghindari penipuan loker bodong</p>
                                        </div>
                                    </div>
                                    <button @click="showModal = false" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition-colors">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>

                                <!-- Body Modal Scrollable -->
                                <div class="p-6 space-y-5 overflow-y-auto flex-1 text-slate-800 text-sm">

                                    <!-- Status Verifikasi -->
                                    <div class="flex items-center justify-between p-4 rounded-2xl {{ $ownerProfile?->profil_lengkap ? 'bg-emerald-50 border border-emerald-300 text-emerald-900' : 'bg-amber-50 border border-amber-300 text-amber-900' }}">
                                        <div class="flex items-center gap-3 font-black">
                                            <i class="fa-solid {{ $ownerProfile?->profil_lengkap ? 'fa-circle-check text-emerald-600 text-xl' : 'fa-triangle-exclamation text-amber-600 text-xl' }}"></i>
                                            <span>Status Profil: {{ $ownerProfile?->profil_lengkap ? 'TERVERIFIKASI & LENGKAP' : 'BELUM LENGKAP' }}</span>
                                        </div>
                                        <span class="text-xs font-bold px-3 py-1 rounded-full {{ $ownerProfile?->profil_lengkap ? 'bg-emerald-200 text-emerald-950' : 'bg-amber-200 text-amber-950' }}">
                                            Akun: {{ $job->user?->name ?? 'Guest/Anonim' }}
                                        </span>
                                    </div>

                                    <!-- Grid Data Pemilik & Usaha -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                            <p class="text-xs font-black text-pink-600 uppercase tracking-wide mb-2"><i class="fa-solid fa-user mr-1"></i> Data Pemilik</p>
                                            <p class="font-black text-slate-900 text-base">{{ $ownerProfile?->nama_pemilik ?? $job->user?->name ?? '-' }}</p>
                                            <p class="text-xs text-slate-500 font-semibold mt-1"><i class="fa-solid fa-envelope mr-1 text-slate-400"></i> {{ $job->user?->email ?? '-' }}</p>
                                        </div>

                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                            <p class="text-xs font-black text-pink-600 uppercase tracking-wide mb-2"><i class="fa-solid fa-store mr-1"></i> Data Usaha</p>
                                            <p class="font-black text-slate-900 text-base">{{ $ownerProfile?->nama_usaha ?? $job->nama_usaha }}</p>
                                            <p class="text-xs text-slate-500 font-semibold mt-1"><i class="fa-solid fa-tag mr-1 text-slate-400"></i> Kategori: {{ $ownerProfile?->kategori_usaha ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <!-- Kontak & Alamat -->
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                                        <p class="text-xs font-black text-pink-600 uppercase tracking-wide"><i class="fa-solid fa-phone mr-1"></i> Kontak & Alamat</p>
                                        <div class="flex items-center gap-3 font-extrabold text-slate-800">
                                            <i class="fa-brands fa-whatsapp text-emerald-600 text-lg"></i>
                                            <span>Nomor WA: {{ $ownerProfile?->no_hp ?? $job->nomor_wa }}</span>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ownerProfile?->no_hp ?? $job->nomor_wa) }}" target="_blank" class="px-2.5 py-1 bg-emerald-500 text-white rounded-lg text-xs font-black hover:bg-emerald-600">
                                                Tes Chat WA
                                            </a>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-600"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> Alamat: {{ $ownerProfile?->alamat_usaha ?? $job->lokasi }}</p>
                                    </div>

                                    <!-- Media Sosial -->
                                    @if($ownerProfile?->medsos_instagram || $ownerProfile?->medsos_tiktok || $ownerProfile?->medsos_facebook || $ownerProfile?->website)
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                            <p class="text-xs font-black text-pink-600 uppercase tracking-wide mb-2"><i class="fa-solid fa-share-nodes mr-1"></i> Media Sosial Usaha</p>
                                            <div class="flex flex-wrap gap-3 font-bold text-xs">
                                                @if($ownerProfile->medsos_instagram)
                                                    <span class="px-3 py-1 bg-pink-100 text-pink-800 rounded-lg"><i class="fa-brands fa-instagram text-pink-600 mr-1"></i> IG: {{ $ownerProfile->medsos_instagram }}</span>
                                                @endif
                                                @if($ownerProfile->medsos_tiktok)
                                                    <span class="px-3 py-1 bg-slate-200 text-slate-800 rounded-lg"><i class="fa-brands fa-tiktok text-slate-900 mr-1"></i> TikTok: {{ $ownerProfile->medsos_tiktok }}</span>
                                                @endif
                                                @if($ownerProfile->medsos_facebook)
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg"><i class="fa-brands fa-facebook text-blue-600 mr-1"></i> FB: {{ $ownerProfile->medsos_facebook }}</span>
                                                @endif
                                                @if($ownerProfile->website)
                                                    <a href="{{ $ownerProfile->website }}" target="_blank" class="px-3 py-1 bg-purple-100 text-purple-800 rounded-lg underline"><i class="fa-solid fa-globe text-purple-600 mr-1"></i> Website</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Bukti Pembayaran Premium -->
                                    @if($job->is_premium || $job->bukti_pembayaran)
                                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200">
                                            <p class="text-xs font-black text-amber-600 uppercase tracking-wide mb-3"><i class="fa-solid fa-money-bill-wave mr-1"></i> Bukti Pembayaran Loker Premium</p>
                                            @if($job->bukti_pembayaran)
                                                <div class="bg-white p-3 rounded-xl border border-amber-200 text-center max-w-sm mx-auto">
                                                    <a href="{{ asset('storage/' . $job->bukti_pembayaran) }}" target="_blank" class="block group relative">
                                                        <img src="{{ asset('storage/' . $job->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="w-full h-48 object-cover rounded-lg border border-slate-200 group-hover:opacity-90 transition-opacity">
                                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center text-white font-black text-xs transition-opacity">
                                                            <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Lihat Gambar Penuh
                                                        </div>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="py-4 bg-white rounded-lg text-amber-500 font-bold text-xs text-center border border-amber-200">
                                                    <i class="fa-solid fa-file-excel text-2xl mb-1 block"></i> Loker diajukan premium, tapi tidak ada bukti pembayaran terlampir.
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Dokumen Identitas KTP / SIM -->
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                        <p class="text-xs font-black text-pink-600 uppercase tracking-wide mb-3"><i class="fa-solid fa-id-card mr-1"></i> Dokumen Identitas Terupload</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            
                                            <!-- Selfie + KTP (revisi verifikasi identitas) -->
                                            <div class="bg-white p-3 rounded-xl border border-slate-200 text-center">
                                                <p class="text-xs font-black text-slate-700 mb-2">Foto Selfie + KTP</p>
                                                @if($ownerProfile?->foto_selfie_ktp)
                                                    <a href="{{ $ownerProfile->foto_selfie_ktp_url }}" target="_blank" rel="noopener" class="block group relative">
                                                        <img src="{{ $ownerProfile->foto_selfie_ktp_url }}" alt="Foto selfie bersama KTP" class="w-full h-36 object-cover rounded-lg border border-slate-200 group-hover:opacity-90 transition-opacity">
                                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center text-white font-black text-xs transition-opacity">
                                                            <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Lihat Gambar Penuh
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="py-8 bg-slate-100 rounded-lg text-slate-400 font-bold text-xs">
                                                        <i class="fa-solid fa-file-circle-xmark text-2xl mb-1 block"></i> Belum ada selfie + KTP
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- SIM -->
                                            <div class="bg-white p-3 rounded-xl border border-slate-200 text-center">
                                                <p class="text-xs font-black text-slate-700 mb-2">Foto SIM</p>
                                                @if($ownerProfile?->foto_sim)
                                                    <a href="{{ asset('storage/' . $ownerProfile->foto_sim) }}" target="_blank" class="block group relative">
                                                        <img src="{{ asset('storage/' . $ownerProfile->foto_sim) }}" alt="Foto SIM" class="w-full h-36 object-cover rounded-lg border border-slate-200 group-hover:opacity-90 transition-opacity">
                                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center text-white font-black text-xs transition-opacity">
                                                            <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Lihat Gambar Penuh
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="py-8 bg-slate-100 rounded-lg text-slate-400 font-bold text-xs">
                                                        <i class="fa-solid fa-file-excel text-2xl mb-1 block"></i> Belum ada foto SIM
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <!-- Footer Modal Keputusan Langsung -->
                                <div class="p-5 bg-slate-100 border-t border-slate-200 flex items-center justify-between gap-3">
                                    <button @click="showModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-black rounded-xl text-xs">
                                        Tutup Pop-up
                                    </button>

                                    @if($job->status == 'pending')
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="is_premium" value="0">
                                                <button type="submit" @disabled($job->hasPaidPremium())
                                                    class="px-3.5 py-2.5 font-bold rounded-xl text-xs {{ $job->hasPaidPremium() ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                                                    <i class="fa-solid fa-check mr-1"></i> Setujui Gratis
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="is_premium" value="1">
                                                <button type="submit" @disabled(! $job->hasPaidPremium())
                                                    class="px-3.5 py-2.5 font-bold rounded-xl text-xs {{ $job->hasPaidPremium() ? 'bg-amber-500 hover:bg-amber-600 text-slate-950' : 'bg-slate-200 text-slate-400 cursor-not-allowed' }}">
                                                    <i class="fa-solid fa-crown mr-1"></i> Setujui Premium
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            <!-- Pagination -->
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @endif

    </div>

</div>

@endsection

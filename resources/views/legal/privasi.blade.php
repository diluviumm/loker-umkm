@extends('layouts.app')

@section('title', 'Kebijakan Privasi - LokerUMKM Kaltim')
@section('description', 'Kebijakan privasi dan perlindungan data pribadi pada portal lowongan kerja UMKM Kalimantan Timur.')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-pink-600 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Kebijakan Privasi</h1>
    <p class="text-xs font-semibold text-slate-500 mt-1">Berlaku sejak 1 November 2026 · Versi 1.0</p>

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-5 text-sm text-slate-700 leading-relaxed">
        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">1. Data yang kami kumpulkan</h2>
            <table class="w-full text-xs sm:text-sm border border-slate-200 rounded-lg overflow-hidden">
                <tr class="bg-pink-50"><th class="text-left p-2 border-b border-slate-200">Kategori</th><th class="text-left p-2 border-b border-slate-200">Contoh data</th></tr>
                <tr><td class="p-2 border-b border-slate-100">Akun</td><td class="p-2 border-b border-slate-100">Nama, email, password (ter-hash), nomor WhatsApp (pemilik usaha)</td></tr>
                <tr><td class="p-2 border-b border-slate-100">Profil pelamar</td><td class="p-2 border-b border-slate-100">Headline, kota, keahlian, CV (PDF)</td></tr>
                <tr><td class="p-2 border-b border-slate-100">Profil usaha</td><td class="p-2 border-b border-slate-100">Nama usaha, alamat, kategori, media sosial, foto selfie+KTP/SIM (opsional)</td></tr>
                <tr><td class="p-2">Aktivitas</td><td class="p-2">Jumlah kunjungan dan klik WhatsApp pada lowongan</td></tr>
            </table>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">2. Tujuan penggunaan</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Menampilkan lowongan dan menghubungkan pelamar dengan pemilik usaha.</li>
                <li>Memverifikasi pemilik usaha agar lowongan yang tayang dapat dipercaya.</li>
                <li>Menampilkan statistik kunjungan kepada pemilik usaha.</li>
                <li>Menjaga keamanan layanan (pencegahan spam dan penyalahgunaan).</li>
            </ul>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">3. Siapa yang dapat melihat data</h2>
            <table class="w-full text-xs sm:text-sm border border-slate-200 rounded-lg overflow-hidden">
                <tr class="bg-pink-50"><th class="text-left p-2 border-b border-slate-200">Data</th><th class="text-left p-2 border-b border-slate-200">Dapat dilihat oleh</th></tr>
                <tr><td class="p-2 border-b border-slate-100">Nomor WhatsApp usaha</td><td class="p-2 border-b border-slate-100">Pelamar yang sudah login, admin</td></tr>
                <tr><td class="p-2 border-b border-slate-100">CV pelamar</td><td class="p-2 border-b border-slate-100">Pelamar itu sendiri, admin</td></tr>
                <tr><td class="p-2 border-b border-slate-100">Selfie + KTP / SIM</td><td class="p-2 border-b border-slate-100">Pemilik usaha itu sendiri, admin</td></tr>
                <tr><td class="p-2">Bukti pembayaran premium</td><td class="p-2">Pemilik usaha itu sendiri, admin</td></tr>
            </table>
            <p class="mt-2">Data tersebut tidak ditampilkan pada halaman publik dan tidak dibagikan ke pihak ketiga untuk tujuan pemasaran.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">4. Penyimpanan dan retensi</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Data disimpan pada server yang dikelola sendiri, dengan akses terbatas.</li>
                <li>Dokumen sensitif (KTP/selfie, CV, bukti bayar) dihapus otomatis setelah tidak diperlukan, dengan batas maksimum <strong>365 hari</strong>.</li>
                <li>Backup harian disimpan maksimal 14 hari.</li>
                <li>Data akun dapat dihapus atas permintaan melalui admin.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">5. Keamanan</h2>
            <p>Kami menerapkan koneksi HTTPS, cookie sesi yang aman, pembatasan percobaan login, serta penyimpanan dokumen di area privat yang hanya dapat diakses pemilik dokumen dan admin.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">6. Hak Anda</h2>
            <p>Anda dapat memperbarui data profil kapan saja melalui dashboard, meminta koreksi atau penghapusan data melalui admin, serta berhenti menggunakan layanan dengan tidak lagi mengakses situs.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">7. Cookie</h2>
            <p>Situs menggunakan cookie sesi untuk menjaga status login dan token keamanan formulir. Kami tidak menggunakan cookie iklan pelacak.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">8. Perubahan kebijakan</h2>
            <p>Perubahan kebijakan akan ditampilkan pada halaman ini beserta tanggal berlakunya.</p>
        </section>
    </div>
</div>
@endsection

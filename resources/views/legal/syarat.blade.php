@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - LokerUMKM Kaltim')
@section('description', 'Syarat dan ketentuan penggunaan portal lowongan kerja UMKM Kalimantan Timur.')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-pink-600 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Syarat &amp; Ketentuan</h1>
    <p class="text-xs font-semibold text-slate-500 mt-1">Berlaku sejak 1 November 2026 · Versi 1.0</p>

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-5 text-sm text-slate-700 leading-relaxed">
        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">1. Tentang layanan</h2>
            <p>LokerUMKM Kalimantan Timur adalah portal yang mempertemukan usaha mikro, kecil, dan menengah (UMKM) di Kalimantan Timur dengan pencari kerja. Kami bukan agen penyalur tenaga kerja dan tidak memungut biaya dari pelamar.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">2. Akun pelamar</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Pendaftaran cukup dengan nama, email, dan password (minimal 8 karakter).</li>
                <li>Nomor WhatsApp pemilik usaha hanya dapat dilihat setelah pelamar masuk ke akun.</li>
                <li>Anda bertanggung jawab menjaga kerahasiaan password dan aktivitas pada akun Anda.</li>
                <li>Data profil (headline, kota, keahlian, CV) bersifat opsional dan dapat Anda ubah kapan saja.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">3. Akun pemilik usaha</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Nomor WhatsApp wajib aktif karena menjadi kontak lamaran dan sarana verifikasi.</li>
                <li>Lowongan yang dikirim berstatus <em>pending</em> dan tayang setelah disetujui admin.</li>
                <li>Masa tayang lowongan adalah 30 hari dan dapat diperpanjang.</li>
                <li>Paket premium bersifat opsional (Rp {{ number_format(config('loker.premium.price'), 0, ',', '.') }}) dan hanya disetujui bila bukti pembayaran valid.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">4. Aturan konten</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Lowongan hanya untuk lokasi di Kalimantan Timur (10 kota/kabupaten yang tersedia).</li>
                <li>Dilarang mencantumkan biaya pendaftaran, deposit, atau iming-iming penghasilan tidak wajar.</li>
                <li>Dilarang mengirim data palsu, lowongan duplikat, atau konten yang menyesatkan.</li>
                <li>Admin berhak menolak atau menghapus lowongan yang melanggar aturan tanpa pemberitahuan sebelumnya.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">5. Lamaran dan komunikasi</h2>
            <p>Lamaran dikirim langsung ke WhatsApp pemilik usaha. Kami tidak menyimpan isi percakapan WhatsApp dan tidak menjamin hasil seleksi. Segala kesepakatan kerja merupakan tanggung jawab pelamar dan pemilik usaha.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">6. Pembatasan tanggung jawab</h2>
            <p>Kami berupaya menjaga kualitas dan keamanan layanan, namun tidak bertanggung jawab atas kerugian yang timbul dari informasi yang diberikan pemilik usaha atau dari komunikasi di luar platform.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">7. Perubahan ketentuan</h2>
            <p>Ketentuan ini dapat diperbarui sewaktu-waktu. Versi terbaru selalu ditampilkan pada halaman ini beserta tanggal berlakunya.</p>
        </section>

        <section>
            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">8. Kontak</h2>
            <p>Pertanyaan mengenai ketentuan ini dapat disampaikan kepada admin melalui halaman <a href="{{ route('home') }}" class="text-pink-700 font-bold hover:underline">beranda</a> atau kontak pengelola yang tercantum pada saat serah terima.</p>
        </section>
    </div>
</div>
@endsection

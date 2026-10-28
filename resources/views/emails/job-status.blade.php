<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Status Loker</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fce7f3; margin: 0; padding: 20px; color: #334155; }
        .container { max-width: 600px; width: 100%; background: #ffffff; margin: 0 auto; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border-top: 6px solid #ec4899; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #be185d; font-size: 24px; margin: 0; }
        .content { line-height: 1.6; font-size: 16px; }
        .job-details { background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #ec4899; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 8px; margin-top: 20px; }
        .btn-premium { background-color: #f59e0b; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Info Loker UMKM</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $job->user->name ?? 'Pemilik Usaha' }}</strong>,</p>
            
            @if(in_array($status, ['approved_gratis', 'approved_premium']))
                <p>Selamat! Lowongan kerja Anda telah <strong>DISETUJUI</strong> dan saat ini sudah tayang di portal kami.</p>
                
                @if($status === 'approved_premium')
                    <p style="color: #d97706; font-weight: bold;">⭐ Loker Anda tayang sebagai Loker Premium dan diprioritaskan di pencarian teratas!</p>
                @endif
            @elseif($status === 'rejected')
                <p>Mohon maaf, lowongan kerja Anda <strong>DITOLAK</strong> oleh tim moderasi kami karena tidak memenuhi panduan komunitas atau terdapat indikasi ketidaksesuaian data.</p>
            @endif

            <div class="job-details">
                <strong>Posisi:</strong> {{ $job->posisi }}<br>
                <strong>Nama Usaha:</strong> {{ $job->nama_usaha }}<br>
                <strong>Tipe Pekerjaan:</strong> {{ $job->tipe_pekerjaan }}<br>
                <strong>Tanggal Pengajuan:</strong> {{ $job->created_at->translatedFormat('d M Y') }}
            </div>

            @if(in_array($status, ['approved_gratis', 'approved_premium']))
                <p style="text-align: center;">
                    <a href="{{ route('jobs.show', $job->slug) }}" class="btn {{ $status === 'approved_premium' ? 'btn-premium' : '' }}">Lihat Loker Anda</a>
                </p>
                <p>Jangan lupa untuk membagikan tautan loker Anda ke berbagai grup atau sosial media agar semakin banyak yang melamar!</p>
            @endif

            <p>Terima kasih telah menggunakan Info Loker UMKM.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Info Loker UMKM. All rights reserved.
        </div>
    </div>
</body>
</html>

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'nama_usaha',
        'posisi',
        'slug',
        'deskripsi',
        'syarat',
        'lokasi',
        'tipe_pekerjaan',
        'gaji',
        'nomor_wa',
        'is_premium',
        'premium_requested',
        'bukti_pembayaran',
        'status',
        'approved_at',
        'expires_at',
        'views_count',
        'clicks_count',
    ];

    protected $casts = [
        'is_premium'        => 'boolean',
        'premium_requested' => 'boolean',
        'approved_at'       => 'datetime',
        'expires_at'        => 'datetime',
    ];

    // ─── Relasi ─────────────────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────────

    /**
     * Loker yang benar-benar tayang: status aktif dan belum kedaluwarsa.
     * Premium selalu di atas, lalu tanggal approve terbaru.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->orderBy('is_premium', 'desc')
            ->orderBy('approved_at', 'desc')
            ->orderBy('created_at', 'desc');
    }

    // ─── Helpers ────────────────────────────────────────────────────

    /**
     * Revisi PR Premium: premium hanya boleh disetujui bila pengajuan premium
     * disertai bukti pembayaran yang tersimpan.
     */
    public function hasPaidPremium(): bool
    {
        return (bool) $this->premium_requested && filled($this->bukti_pembayaran);
    }

    /**
     * Format nomor WA → format internasional 62xxx.
     * Menangani 08xx, 628xx, +62 8xx, dan 8xx (tanpa awalan).
     */
    public function getFormattedWaNumberAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $this->nomor_wa);

        if (Str::startsWith($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (Str::startsWith($phone, '8')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Link WA dengan pesan otomatis.
     */
    public function getWaLinkAttribute(): string
    {
        $phone = $this->formatted_wa_number;
        $pesan = "Halo {$this->nama_usaha}, saya bermaksud melamar untuk posisi *{$this->posisi}* yang saya lihat di Info Loker UMKM. Apakah posisi ini masih tersedia?";

        return "https://wa.me/{$phone}?text=" . urlencode($pesan);
    }
}

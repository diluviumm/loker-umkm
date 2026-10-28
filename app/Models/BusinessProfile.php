<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pemilik',
        'nama_usaha',
        'no_hp',
        'alamat_usaha',
        'kategori_usaha',
        'deskripsi_usaha',
        'medsos_instagram',
        'medsos_tiktok',
        'medsos_facebook',
        'website',
        'foto_ktp',
        'foto_selfie_ktp',
        'foto_sim',
        'profil_lengkap',
    ];

    protected $casts = [
        'profil_lengkap' => 'boolean',
    ];

    // ─── Relasi ─────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ────────────────────────────────────────────────────
    public function getFotoKtpUrlAttribute(): ?string
    {
        return $this->foto_ktp ? asset('storage/' . $this->foto_ktp) : null;
    }

    /**
     * Revisi: verifikasi identitas memakai selfie + KTP.
     */
    public function getFotoSelfieKtpUrlAttribute(): ?string
    {
        return $this->foto_selfie_ktp ? route('documents.show', ['path' => $this->foto_selfie_ktp]) : null;
    }

    public function getFotoSimUrlAttribute(): ?string
    {
        return $this->foto_sim ? route('documents.show', ['path' => $this->foto_sim]) : null;
    }
}

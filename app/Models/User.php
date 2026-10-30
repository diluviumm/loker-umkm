<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'super_admin';
    public const ROLE_OWNER = 'pemilik_usaha';
    public const ROLE_SEEKER = 'pelamar';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // ─── Role Helpers ───────────────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPemilikUsaha(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isPelamar(): bool
    {
        return $this->role === self::ROLE_SEEKER;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Super Admin',
            self::ROLE_OWNER => 'Pemilik Usaha',
            self::ROLE_SEEKER => 'Pelamar',
            default => ucfirst((string) $this->role),
        };
    }

    // ─── Relasi ─────────────────────────────────────────────────────
    public function businessProfile()
    {
        return $this->hasOne(BusinessProfile::class);
    }

    public function jobListings()
    {
        return $this->hasMany(JobListing::class);
    }

    public function seekerProfile()
    {
        return $this->hasOne(SeekerProfile::class);
    }

    public function savedJobs()
    {
        return $this->belongsToMany(JobListing::class, 'saved_jobs')
            ->withTimestamps();
    }
}

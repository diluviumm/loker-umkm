<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\WhatsAppLoginController;
use App\Http\Controllers\Business\DashboardController as BusinessDashboard;
use App\Http\Controllers\Business\JobPostController;
use App\Http\Controllers\Business\ProfileController as BusinessProfile;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobDetailController;
use App\Http\Controllers\JobTrackController;
use App\Http\Controllers\Pelamar\DashboardController as PelamarDashboard;
use App\Http\Controllers\Pelamar\ProfileController as PelamarProfile;
use App\Http\Controllers\Pelamar\SavedJobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Portal Lowongan Kerja UMKM
|--------------------------------------------------------------------------
*/

// ─── PUBLIC: Pelamar / Pengunjung (tidak perlu login) ──────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/loker/{slug}', [JobDetailController::class, 'show'])->name('jobs.show');
Route::post('/loker/{job}/track-click', [JobTrackController::class, 'trackClick'])
    ->middleware('throttle:30,1')
    ->name('jobs.track-click');

// ─── AUTH: Login & Register ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.post');

    Route::get('/daftar', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.post');

    // Login via WhatsApp (OTP Fonnte)
    Route::get('/login-wa', [WhatsAppLoginController::class, 'showForm'])->name('login.wa');
    Route::post('/login-wa/kirim', [WhatsAppLoginController::class, 'send'])
        ->middleware('throttle:3,1')
        ->name('login.wa.send');
    Route::post('/login-wa/verifikasi', [WhatsAppLoginController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('login.wa.verify');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── BUSINESS OWNER ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:pemilik_usaha'])->prefix('usaha')->name('business.')->group(function () {
    Route::get('/dashboard', [BusinessDashboard::class, 'index'])->name('dashboard');

    Route::get('/profil', [BusinessProfile::class, 'edit'])->name('profile.edit');
    Route::post('/profil', [BusinessProfile::class, 'update'])->name('profile.update');

    Route::get('/pasang-loker', [JobPostController::class, 'create'])->name('jobs.create');
    Route::post('/pasang-loker', [JobPostController::class, 'store'])->name('jobs.store');
    Route::delete('/loker/{job}', [JobPostController::class, 'destroy'])->name('jobs.destroy');
    Route::post('/loker/{job}/renew', [JobPostController::class, 'renew'])->name('jobs.renew');
    Route::post('/loker/{job}/close', [JobPostController::class, 'close'])->name('jobs.close');
});

// ─── PELAMAR (pencari kerja) ───────────────────────────────────────────
Route::middleware(['auth', 'role:pelamar'])->prefix('pelamar')->name('pelamar.')->group(function () {
    Route::get('/dashboard', [PelamarDashboard::class, 'index'])->name('dashboard');
    Route::get('/profil', [PelamarProfile::class, 'edit'])->name('profile.edit');
    Route::post('/profil', [PelamarProfile::class, 'update'])->name('profile.update');
    Route::post('/simpan/{job}', [SavedJobController::class, 'store'])->name('saved.store');
    Route::delete('/simpan/{job}', [SavedJobController::class, 'destroy'])->name('saved.destroy');
});

// ─── ADMIN ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/loker/{job}/approve', [AdminDashboardController::class, 'approve'])->name('jobs.approve');
    Route::post('/loker/{job}/reject', [AdminDashboardController::class, 'reject'])->name('jobs.reject');
    Route::post('/loker/{job}/toggle-premium', [AdminDashboardController::class, 'togglePremium'])->name('jobs.toggle-premium');
    Route::delete('/loker/{job}', [AdminDashboardController::class, 'destroy'])->name('jobs.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminDashboardController::class, 'loginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminDashboardController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('admin.login.post');
});

Route::post('/admin/logout', [AdminDashboardController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

// ─── DOKUMEN PRIVAT (selfie+KTP, SIM, bukti bayar) ─────────────────────
Route::get('/dokumen/{path}', [DocumentController::class, 'show'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('documents.show');

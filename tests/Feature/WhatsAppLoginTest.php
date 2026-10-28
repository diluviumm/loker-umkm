<?php

namespace Tests\Feature;

use App\Models\OtpCode;
use App\Models\User;
use App\Services\WhatsAppOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_nomor_belum_terdaftar_ditolak(): void
    {
        $this->post('/login-wa/kirim', ['phone' => '081200000000'])
            ->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('otp_codes', 0);
    }

    public function test_kirim_otp_untuk_nomor_terdaftar(): void
    {
        User::factory()->create(['phone' => '6281234500011']);

        $this->post('/login-wa/kirim', ['phone' => '081234500011'])
            ->assertSessionHas('otp_phone', '6281234500011');

        $this->assertDatabaseCount('otp_codes', 1);
    }

    public function test_verifikasi_kode_benar_login(): void
    {
        $user = User::factory()->create(['phone' => '6281234500011']);

        $result = app(WhatsAppOtpService::class)->request('081234500011');

        $this->withSession(['otp_phone' => '6281234500011'])
            ->post('/login-wa/verifikasi', ['code' => $result['code']])
            ->assertRedirect('/usaha/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_verifikasi_kode_salah_ditolak(): void
    {
        User::factory()->create(['phone' => '6281234500011']);

        app(WhatsAppOtpService::class)->request('081234500011');

        $this->withSession(['otp_phone' => '6281234500011'])
            ->post('/login-wa/verifikasi', ['code' => '000000'])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }

    public function test_kode_kedaluwarsa_tidak_bisa_dipakai(): void
    {
        $user = User::factory()->create(['phone' => '6281234500011']);

        $result = app(WhatsAppOtpService::class)->request('081234500011');
        OtpCode::query()->update(['expires_at' => now()->subMinute()]);

        $this->withSession(['otp_phone' => '6281234500011'])
            ->post('/login-wa/verifikasi', ['code' => $result['code']])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }
}

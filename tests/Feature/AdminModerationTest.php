<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModerationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin']);
    }

    private function owner(): User
    {
        return User::factory()->create(['role' => 'pemilik_usaha']);
    }

    private function pendingJob(array $overrides = []): JobListing
    {
        $category = Category::firstOrCreate(
            ['slug' => 'kuliner'],
            ['nama' => 'Kuliner', 'icon' => 'fa-utensils']
        );

        return JobListing::create(array_merge([
            'user_id'          => $this->owner()->id,
            'category_id'      => $category->id,
            'nama_usaha'       => 'Warung Test',
            'posisi'           => 'Kasir',
            'slug'             => 'kasir-warung-test',
            'deskripsi'        => 'Melayani pelanggan dan mengelola transaksi harian.',
            'lokasi'           => 'Bandung',
            'tipe_pekerjaan'   => 'Full Time',
            'nomor_wa'         => '081234567890',
            'status'           => 'pending',
            'is_premium'       => false,
            'premium_requested'=> false,
            'bukti_pembayaran' => null,
        ], $overrides));
    }

    public function test_tamu_diarahkan_ke_login_saat_membuka_dashboard_admin(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
    }

    public function test_pemilik_usaha_tidak_boleh_membuka_dashboard_admin(): void
    {
        $this->actingAs($this->owner())
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_admin_menyetujui_loker_gratis_tanpa_bukti_bayar(): void
    {
        $job = $this->pendingJob();

        $this->actingAs($this->admin())
            ->post("/admin/loker/{$job->id}/approve", ['is_premium' => '0'])
            ->assertRedirect();

        $job->refresh();
        $this->assertSame('aktif', $job->status);
        $this->assertFalse($job->is_premium);
        $this->assertNotNull($job->approved_at);
        $this->assertNotNull($job->expires_at);
    }

    public function test_admin_tidak_bisa_menyetujui_premium_tanpa_bukti_bayar(): void
    {
        $job = $this->pendingJob(['premium_requested' => true]);

        $this->actingAs($this->admin())
            ->post("/admin/loker/{$job->id}/approve", ['is_premium' => '1'])
            ->assertSessionHas('error');

        $job->refresh();
        $this->assertSame('pending', $job->status);
        $this->assertFalse($job->is_premium);
    }

    public function test_admin_menyetujui_premium_otomatis_saat_bukti_bayar_ada(): void
    {
        $job = $this->pendingJob([
            'premium_requested' => true,
            'bukti_pembayaran'  => 'pembayaran/bukti.png',
        ]);

        $this->actingAs($this->admin())
            ->post("/admin/loker/{$job->id}/approve", ['is_premium' => '1'])
            ->assertRedirect();

        $job->refresh();
        $this->assertSame('aktif', $job->status);
        $this->assertTrue($job->is_premium);
        $this->assertTrue($job->expires_at->greaterThan(now()->addDays(29)));
    }

    public function test_persetujuan_gratis_ditolak_saat_loker_sudah_dibayar(): void
    {
        $job = $this->pendingJob([
            'premium_requested' => true,
            'bukti_pembayaran'  => 'pembayaran/bukti.png',
        ]);

        $this->actingAs($this->admin())
            ->post("/admin/loker/{$job->id}/approve", ['is_premium' => '0'])
            ->assertSessionHas('error');

        $this->assertSame('pending', $job->fresh()->status);
    }

    public function test_login_admin_menolak_akun_pemilik_usaha(): void
    {
        $owner = $this->owner();

        $this->post('/admin/login', [
            'email'    => $owner->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_admin_berhasil_untuk_super_admin(): void
    {
        $admin = $this->admin();

        $this->post('/admin/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ])->assertRedirect('/admin/dashboard');

        $this->assertAuthenticatedAs($admin);
    }
}

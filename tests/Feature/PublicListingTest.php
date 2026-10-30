<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicListingTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::firstOrCreate(
            ['slug' => 'kuliner'],
            ['nama' => 'Kuliner', 'icon' => 'fa-utensils']
        );
    }

    private function job(array $overrides = []): JobListing
    {
        return JobListing::create(array_merge([
            'category_id'    => $this->category()->id,
            'nama_usaha'     => 'Warung Test',
            'posisi'         => 'Kasir',
            'slug'           => 'kasir-warung-test',
            'deskripsi'      => 'Melayani pelanggan dan mengelola transaksi harian.',
            'lokasi'         => 'Samarinda',
            'tipe_pekerjaan' => 'Full Time',
            'nomor_wa'       => '081234567890',
            'status'         => 'aktif',
            'is_premium'     => false,
            'approved_at'    => now(),
            'expires_at'     => now()->addDays(30),
        ], $overrides));
    }

    public function test_home_hanya_menampilkan_loker_aktif(): void
    {
        $this->job(['posisi' => 'Kasir Aktif', 'slug' => 'kasir-aktif']);
        $this->job(['posisi' => 'Kasir Pending', 'slug' => 'kasir-pending', 'status' => 'pending']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Kasir Aktif')
            ->assertDontSee('Kasir Pending');
    }

    public function test_detail_loker_pending_tidak_boleh_diakses_publik(): void
    {
        $this->job(['slug' => 'loker-pending', 'status' => 'pending']);

        $this->get('/loker/loker-pending')->assertNotFound();
    }

    public function test_detail_loker_kedaluwarsa_tidak_boleh_diakses_publik(): void
    {
        $this->job(['slug' => 'loker-expired', 'expires_at' => now()->subDay()]);

        $this->get('/loker/loker-expired')->assertNotFound();
    }

    public function test_detail_loker_aktif_tampil_dan_menambah_views(): void
    {
        $job = $this->job(['slug' => 'kasir-aktif']);

        $this->get('/loker/kasir-aktif')->assertOk()->assertSee('Kasir');

        $this->assertSame(1, $job->fresh()->views_count);
    }

    public function test_loker_premium_tampil_lebih_dulu_daripada_loker_gratis(): void
    {
        $this->job(['posisi' => 'Loker Gratis', 'slug' => 'loker-gratis', 'is_premium' => false, 'approved_at' => now()]);
        $this->job(['posisi' => 'Loker Premium', 'slug' => 'loker-premium', 'is_premium' => true, 'approved_at' => now()->subDay()]);

        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder(['Loker Premium', 'Loker Gratis']);
    }

    public function test_track_click_butuh_login(): void
    {
        $job = JobListing::create([
            'category_id'    => Category::firstOrCreate(['slug' => 'kuliner'], ['nama' => 'Kuliner', 'icon' => 'fa-utensils'])->id,
            'nama_usaha'     => 'Warung Test',
            'posisi'         => 'Kasir',
            'slug'           => 'kasir-warung-track-guard',
            'deskripsi'      => 'Melayani pelanggan dan mengelola transaksi harian.',
            'lokasi'         => 'Samarinda',
            'tipe_pekerjaan' => 'Full Time',
            'nomor_wa'       => '6281234567890',
            'status'         => 'aktif',
            'approved_at'    => now(),
            'expires_at'     => now()->addDays(30),
        ]);

        $this->postJson("/loker/{$job->id}/track-click")->assertUnauthorized();
    }

    public function test_track_click_ditolak_untuk_loker_tidak_aktif(): void
    {
        $job = $this->job(['slug' => 'loker-pending', 'status' => 'pending']);

        $this->actingAs(User::factory()->pelamar()->create())
            ->postJson("/loker/{$job->id}/track-click")->assertStatus(422);

        $this->assertSame(0, $job->fresh()->clicks_count);
    }

    public function test_track_click_menambah_counter_untuk_loker_aktif(): void
    {
        $job = $this->job(['slug' => 'loker-aktif']);

        $this->actingAs(User::factory()->pelamar()->create())
            ->postJson("/loker/{$job->id}/track-click")
            ->assertOk()
            ->assertJson(['success' => true, 'clicks_count' => 1]);

        $this->assertSame(1, $job->fresh()->clicks_count);
    }
}

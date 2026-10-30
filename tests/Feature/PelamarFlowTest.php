<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelamarFlowTest extends TestCase
{
    use RefreshDatabase;

    private function job(): JobListing
    {
        $category = Category::firstOrCreate(
            ['slug' => 'kuliner'],
            ['nama' => 'Kuliner', 'icon' => 'fa-utensils']
        );

        return JobListing::create([
            'category_id'    => $category->id,
            'nama_usaha'     => 'Warung Test',
            'posisi'         => 'Kasir',
            'slug'           => 'kasir-warung-test',
            'deskripsi'      => 'Melayani pelanggan dan mengelola transaksi harian.',
            'lokasi'         => 'Samarinda',
            'tipe_pekerjaan' => 'Full Time',
            'nomor_wa'       => '6281234567890',
            'status'         => 'aktif',
            'is_premium'     => false,
            'approved_at'    => now(),
            'expires_at'     => now()->addDays(30),
        ]);
    }

    public function test_pendaftaran_pelamar_cukup_email_dan_password(): void
    {
        $this->post('/daftar/pelamar', [
            'name'                  => 'Rina Pencari Kerja',
            'email'                 => 'rina@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'terms'                 => '1',
        ])->assertRedirect('/pelamar/dashboard');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'rina@example.test',
            'phone' => null,
            'role'  => 'pelamar',
        ]);
    }

    public function test_halaman_daftar_menawarkan_dua_jenis_akun(): void
    {
        $this->get('/daftar')
            ->assertOk()
            ->assertSee('Daftar sebagai pelamar')
            ->assertSee('Daftar sebagai pemilik usaha');
    }

    public function test_pelamar_bisa_menyimpan_dan_menghapus_loker(): void
    {
        $pelamar = User::factory()->pelamar()->create();
        $job = $this->job();

        $this->actingAs($pelamar)
            ->post("/pelamar/simpan/{$job->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('saved_jobs', [
            'user_id'        => $pelamar->id,
            'job_listing_id' => $job->id,
        ]);

        $this->actingAs($pelamar)
            ->get('/pelamar/dashboard')
            ->assertOk()
            ->assertSee('Kasir');

        $this->actingAs($pelamar)
            ->delete("/pelamar/simpan/{$job->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('saved_jobs', [
            'user_id'        => $pelamar->id,
            'job_listing_id' => $job->id,
        ]);
    }

    public function test_loker_tidak_aktif_tidak_bisa_disimpan(): void
    {
        $pelamar = User::factory()->pelamar()->create();
        $job = $this->job();
        $job->update(['status' => 'pending']);

        $this->actingAs($pelamar)
            ->post("/pelamar/simpan/{$job->id}")
            ->assertSessionHas('error');

        $this->assertDatabaseCount('saved_jobs', 0);
    }

    public function test_pelamar_tidak_bisa_masuk_area_pemilik_usaha(): void
    {
        $this->actingAs(User::factory()->pelamar()->create())
            ->get('/usaha/dashboard')
            ->assertForbidden();
    }

    public function test_pemilik_usaha_tidak_bisa_masuk_area_pelamar(): void
    {
        $this->actingAs(User::factory()->pemilikUsaha()->create())
            ->get('/pelamar/dashboard')
            ->assertForbidden();
    }
}

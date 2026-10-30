<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationAndContactTest extends TestCase
{
    use RefreshDatabase;

    private function job(): JobListing
    {
        $category = Category::firstOrCreate(['slug' => 'kuliner'], ['nama' => 'Kuliner', 'icon' => 'fa-utensils']);

        return JobListing::create([
            'category_id'    => $category->id,
            'nama_usaha'     => 'Warung Kaltim',
            'posisi'         => 'Kasir',
            'slug'           => 'kasir-warung-kaltim',
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

    public function test_tamu_melihat_kontak_wa_terkunci(): void
    {
        $job = $this->job();

        $this->get('/loker/' . $job->slug)
            ->assertOk()
            ->assertDontSee('6281234567890')
            ->assertSee('Buat akun untuk lihat WA')
            ->assertSee('Buat Akun Pelamar');
    }

    public function test_pelamar_login_melihat_nomor_wa(): void
    {
        $job = $this->job();

        $this->actingAs(User::factory()->pelamar()->create())
            ->get('/loker/' . $job->slug)
            ->assertOk()
            ->assertSee('6281234567890')
            ->assertSee('Lamar via WhatsApp');
    }

    public function test_pendaftaran_usaha_wajib_nomor_whatsapp(): void
    {
        $this->post('/daftar/usaha', [
            'name'                  => 'Pak Budi',
            'email'                 => 'budi@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'terms'                 => '1',
        ])->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_pendaftaran_usaha_membuat_akun_pemilik(): void
    {
        $this->post('/daftar/usaha', [
            'name'                  => 'Pak Budi',
            'email'                 => 'budi@example.test',
            'phone'                 => '0812-3456-7890',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'terms'                 => '1',
        ])->assertRedirect('/usaha/profil');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.test',
            'phone' => '6281234567890',
            'role'  => 'pemilik_usaha',
        ]);
    }

    public function test_lokasi_di_luar_kaltim_ditolak(): void
    {
        $owner = User::factory()->pemilikUsaha()->create();
        BusinessProfile::create([
            'user_id'        => $owner->id,
            'nama_pemilik'   => 'Sari',
            'nama_usaha'     => 'Kopi Sari',
            'no_hp'          => '081234567890',
            'profil_lengkap' => true,
        ]);
        $category = Category::create(['nama' => 'Kuliner', 'slug' => 'kuliner', 'icon' => 'fa-utensils']);

        $this->actingAs($owner)->post('/usaha/pasang-loker', [
            'category_id'    => $category->id,
            'posisi'         => 'Barista',
            'deskripsi'      => str_repeat('Membuat minuman kopi. ', 3),
            'lokasi'         => 'Bandung',
            'tipe_pekerjaan' => 'Full Time',
        ])->assertSessionHasErrors('lokasi');

        $this->assertSame(0, JobListing::count());
    }

    public function test_lokasi_kaltim_diterima(): void
    {
        $owner = User::factory()->pemilikUsaha()->create();
        BusinessProfile::create([
            'user_id'        => $owner->id,
            'nama_pemilik'   => 'Sari',
            'nama_usaha'     => 'Kopi Sari',
            'no_hp'          => '081234567890',
            'profil_lengkap' => true,
        ]);
        $category = Category::create(['nama' => 'Kuliner', 'slug' => 'kuliner', 'icon' => 'fa-utensils']);

        $this->actingAs($owner)->post('/usaha/pasang-loker', [
            'category_id'    => $category->id,
            'posisi'         => 'Barista',
            'deskripsi'      => str_repeat('Membuat minuman kopi. ', 3),
            'lokasi'         => 'Samarinda',
            'tipe_pekerjaan' => 'Full Time',
        ])->assertRedirect('/usaha/dashboard');

        $this->assertSame(1, JobListing::count());
        $this->assertSame('Samarinda', JobListing::first()->lokasi);
    }

    public function test_admin_melihat_traffic_pendaftar(): void
    {
        $pelamar = User::factory()->pelamar()->create(['name' => 'Rina Pelamar']);
        $owner = User::factory()->pemilikUsaha()->create(['name' => 'Budi Pemilik']);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/pengguna')
            ->assertOk()
            ->assertSee('Rina Pelamar')
            ->assertSee('Budi Pemilik')
            ->assertSee('Akun Pelamar');
    }

    public function test_pelamar_tidak_bisa_membuka_halaman_pengguna_admin(): void
    {
        $this->actingAs(User::factory()->pelamar()->create())
            ->get('/admin/pengguna')
            ->assertForbidden();
    }
}

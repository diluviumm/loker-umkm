<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BusinessFlowTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): User
    {
        return User::factory()->create(['role' => 'pemilik_usaha']);
    }

    public function test_profil_menyimpan_foto_selfie_bersama_ktp(): void
    {
        Storage::fake('public');
        $owner = $this->owner();

        $this->actingAs($owner)->post('/usaha/profil', [
            'nama_pemilik'    => 'Sari',
            'nama_usaha'      => 'Kopi Sari',
            'no_hp'           => '081234567890',
            'foto_selfie_ktp' => UploadedFile::fake()->image('selfie-ktp.jpg', 800, 600),
        ])->assertRedirect('/usaha/dashboard');

        $profile = $owner->fresh()->businessProfile;

        $this->assertNotNull($profile);
        $this->assertTrue($profile->profil_lengkap);
        $this->assertNotNull($profile->foto_selfie_ktp);
        Storage::disk('local')->assertExists($profile->foto_selfie_ktp);
    }

    public function test_profil_menolak_file_selain_gambar(): void
    {
        Storage::fake('public');

        $this->actingAs($this->owner())->post('/usaha/profil', [
            'nama_pemilik'    => 'Sari',
            'nama_usaha'      => 'Kopi Sari',
            'no_hp'           => '081234567890',
            'foto_selfie_ktp' => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('foto_selfie_ktp');
    }

    public function test_posting_loker_premium_wajib_menyertakan_bukti_pembayaran(): void
    {
        Storage::fake('public');
        $owner = $this->owner();
        $owner->businessProfile()->create([
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
            'is_premium'     => '1',
        ])->assertSessionHasErrors('bukti_pembayaran');

        $this->assertSame(0, JobListing::count());
    }

    public function test_posting_loker_premium_membuat_pengajuan_tanpa_langsung_premium(): void
    {
        Storage::fake('public');
        $owner = $this->owner();
        $owner->businessProfile()->create([
            'nama_pemilik'   => 'Sari',
            'nama_usaha'     => 'Kopi Sari',
            'no_hp'          => '081234567890',
            'profil_lengkap' => true,
        ]);
        $category = Category::create(['nama' => 'Kuliner', 'slug' => 'kuliner', 'icon' => 'fa-utensils']);

        $this->actingAs($owner)->post('/usaha/pasang-loker', [
            'category_id'      => $category->id,
            'posisi'           => 'Barista',
            'deskripsi'        => str_repeat('Membuat minuman kopi. ', 3),
            'lokasi'           => 'Samarinda',
            'tipe_pekerjaan'   => 'Full Time',
            'is_premium'       => '1',
            'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect('/usaha/dashboard');

        $job = JobListing::firstOrFail();

        $this->assertSame('pending', $job->status);
        $this->assertTrue($job->premium_requested);
        $this->assertFalse($job->is_premium);
        $this->assertTrue($job->hasPaidPremium());
    }

    public function test_posting_loker_ditolak_bila_profil_belum_lengkap(): void
    {
        $owner = $this->owner();
        $category = Category::create(['nama' => 'Kuliner', 'slug' => 'kuliner', 'icon' => 'fa-utensils']);

        $this->actingAs($owner)->post('/usaha/pasang-loker', [
            'category_id'    => $category->id,
            'posisi'         => 'Barista',
            'deskripsi'      => str_repeat('Membuat minuman kopi. ', 3),
            'lokasi'         => 'Samarinda',
            'tipe_pekerjaan' => 'Full Time',
        ])->assertRedirect('/usaha/profil');

        $this->assertSame(0, JobListing::count());
    }
}

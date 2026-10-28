<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeekerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelamar_menyimpan_profil_dan_cv(): void
    {
        Storage::fake('local');
        $pelamar = User::factory()->pelamar()->create();

        $this->actingAs($pelamar)->post('/pelamar/profil', [
            'headline' => 'Barista 2 tahun',
            'city'     => 'Bandung',
            'skills'   => 'Manual brew, kasir, stok harian.',
            'cv'       => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
        ])->assertRedirect('/pelamar/dashboard');

        $profile = $pelamar->fresh()->seekerProfile;

        $this->assertNotNull($profile);
        $this->assertSame('Barista 2 tahun', $profile->headline);
        $this->assertNotNull($profile->cv_path);
        Storage::disk('local')->assertExists($profile->cv_path);
    }

    public function test_cv_harus_pdf(): void
    {
        Storage::fake('local');

        $this->actingAs(User::factory()->pelamar()->create())
            ->post('/pelamar/profil', [
                'headline' => 'Kasir',
                'cv'       => UploadedFile::fake()->image('cv.jpg'),
            ])
            ->assertSessionHasErrors('cv');
    }

    public function test_cv_hanya_bisa_dibuka_pemilik_dan_admin(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('private/cv/saya.pdf', '%PDF-1.4');

        $pelamar = User::factory()->pelamar()->create();
        $pelamar->seekerProfile()->create(['cv_path' => 'private/cv/saya.pdf']);

        $this->actingAs($pelamar)->get('/dokumen/private/cv/saya.pdf')->assertOk();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/dokumen/private/cv/saya.pdf')->assertOk();

        $this->actingAs(User::factory()->pelamar()->create())
            ->get('/dokumen/private/cv/saya.pdf')->assertForbidden();
    }

    public function test_pemilik_usaha_tidak_bisa_membuka_profil_pelamar(): void
    {
        $this->actingAs(User::factory()->pemilikUsaha()->create())
            ->get('/pelamar/profil')
            ->assertForbidden();
    }
}

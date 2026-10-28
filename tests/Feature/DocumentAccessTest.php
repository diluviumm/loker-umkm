<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentAccessTest extends TestCase
{
    use RefreshDatabase;

    private const PATH = 'private/selfie-ktp/bukti.jpg';

    private function ownerWithSelfie(): User
    {
        Storage::fake('local');
        Storage::disk('local')->put(self::PATH, 'fake-image-bytes');

        $owner = User::factory()->pemilikUsaha()->create();

        BusinessProfile::create([
            'user_id'          => $owner->id,
            'nama_pemilik'     => 'Sari',
            'nama_usaha'       => 'Kopi Sari',
            'no_hp'            => '6281234567890',
            'foto_selfie_ktp'  => self::PATH,
            'profil_lengkap'   => true,
        ]);

        return $owner;
    }

    public function test_pemilik_bisa_melihat_dokumennya_sendiri(): void
    {
        $owner = $this->ownerWithSelfie();

        $this->actingAs($owner)
            ->get('/dokumen/' . self::PATH)
            ->assertOk();
    }

    public function test_pemilik_lain_tidak_boleh_melihat(): void
    {
        $this->ownerWithSelfie();

        $this->actingAs(User::factory()->pemilikUsaha()->create())
            ->get('/dokumen/' . self::PATH)
            ->assertForbidden();
    }

    public function test_admin_boleh_melihat_semua_dokumen(): void
    {
        $this->ownerWithSelfie();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/dokumen/' . self::PATH)
            ->assertOk();
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->ownerWithSelfie();

        $this->get('/dokumen/' . self::PATH)->assertRedirect('/login');
    }

    public function test_file_di_luar_folder_dokumen_ditolak(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/dokumen/config/app.php')
            ->assertNotFound();
    }

    public function test_path_traversal_ditolak(): void
    {
        $owner = $this->ownerWithSelfie();

        $this->actingAs($owner)
            ->get('/dokumen/..%2F..%2F.env')
            ->assertNotFound();
    }
}

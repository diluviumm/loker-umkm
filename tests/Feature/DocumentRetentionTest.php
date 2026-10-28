<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\Category;
use App\Models\DocumentPrune;
use App\Models\JobListing;
use App\Models\SeekerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentRetentionTest extends TestCase
{
    use RefreshDatabase;

    private function oldJob(string $status = 'aktif', string $proof = 'private/pembayaran/bukti.pdf'): JobListing
    {
        Storage::disk('local')->put($proof, 'PDF');
        $category = Category::firstOrCreate(['slug' => 'kuliner'], ['nama' => 'Kuliner', 'icon' => 'fa-utensils']);

        $job = JobListing::create([
            'user_id'        => User::factory()->pemilikUsaha()->create()->id,
            'category_id'    => $category->id,
            'nama_usaha'     => 'Warung Uji',
            'posisi'         => 'Kasir',
            'slug'           => 'kasir-warung-uji-'.uniqid(),
            'deskripsi'      => 'Melayani pembeli dan menjaga kebersihan warung.',
            'lokasi'         => 'Bandung',
            'tipe_pekerjaan' => 'Full Time',
            'nomor_wa'       => '6281234500099',
            'status'         => $status,
            'bukti_pembayaran' => $proof,
        ]);

        DB::table('job_listings')->where('id', $job->id)->update(['updated_at' => now()->subDays(400)]);

        return $job->fresh();
    }

    public function test_pratinjau_tidak_menghapus_apa_pun(): void
    {
        Storage::fake('local');
        $job = $this->oldJob();

        $this->artisan('documents:prune', ['--days' => 365])
            ->expectsOutputToContain('PRATINJAU')
            ->assertSuccessful();

        Storage::disk('local')->assertExists($job->bukti_pembayaran);
        $this->assertSame(0, DocumentPrune::count());
    }

    public function test_force_menghapus_bukti_bayar_loker_final(): void
    {
        Storage::fake('local');
        $job = $this->oldJob('aktif');

        $this->artisan('documents:prune', ['--days' => 365, '--force' => true])
            ->assertSuccessful();

        Storage::disk('local')->assertMissing('private/pembayaran/bukti.pdf');
        $this->assertNull($job->fresh()->bukti_pembayaran);
        $this->assertSame(1, DocumentPrune::where('type', 'payment_proof')->count());
    }

    public function test_bukti_loker_pending_tidak_dihapus(): void
    {
        Storage::fake('local');
        $job = $this->oldJob('pending');

        $this->artisan('documents:prune', ['--days' => 365, '--force' => true])
            ->assertSuccessful();

        Storage::disk('local')->assertExists($job->bukti_pembayaran);
    }

    public function test_identitas_dihapus_hanya_bila_sudah_dimoderasi(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('private/ktp/ktp.jpg', 'IMG');
        Storage::disk('local')->put('private/selfie-ktp/selfie.jpg', 'IMG');

        $owner = User::factory()->pemilikUsaha()->create();
        $profile = BusinessProfile::create([
            'user_id'         => $owner->id,
            'nama_pemilik'    => 'Budi',
            'nama_usaha'      => 'Toko Budi',
            'no_hp'           => '6281234500077',
            'alamat_usaha'    => 'Jl. Uji 1',
            'kategori_usaha'  => 'Retail',
            'deskripsi_usaha' => 'Toko kelontong',
            'foto_ktp'        => 'private/ktp/ktp.jpg',
            'foto_selfie_ktp' => 'private/selfie-ktp/selfie.jpg',
        ]);
        DB::table('business_profiles')->where('id', $profile->id)->update(['updated_at' => now()->subDays(400)]);

        // Belum punya loker aktif → dokumen identitas masih diperlukan.
        $this->artisan('documents:prune', ['--days' => 365, '--force' => true])->assertSuccessful();
        Storage::disk('local')->assertExists('private/ktp/ktp.jpg');

        // Setelah lolos moderasi (punya loker aktif) → boleh dihapus.
        $category = Category::firstOrCreate(['slug' => 'retail'], ['nama' => 'Retail', 'icon' => 'fa-store']);
        JobListing::create([
            'user_id' => $owner->id, 'category_id' => $category->id, 'nama_usaha' => 'Toko Budi',
            'posisi' => 'Kasir', 'slug' => 'kasir-toko-budi', 'deskripsi' => 'Menjaga kasir dan stok toko.',
            'lokasi' => 'Bandung', 'tipe_pekerjaan' => 'Full Time', 'nomor_wa' => '6281234500077',
            'status' => 'aktif',
        ]);

        $this->artisan('documents:prune', ['--days' => 365, '--force' => true])->assertSuccessful();

        Storage::disk('local')->assertMissing('private/ktp/ktp.jpg');
        Storage::disk('local')->assertMissing('private/selfie-ktp/selfie.jpg');
        $this->assertNull($profile->fresh()->foto_ktp);
    }

    public function test_cv_lama_dihapus(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('private/cv/lama.pdf', 'PDF');

        $pelamar = User::factory()->pelamar()->create();
        $profile = SeekerProfile::create(['user_id' => $pelamar->id, 'headline' => 'Kasir', 'cv_path' => 'private/cv/lama.pdf']);
        DB::table('seeker_profiles')->where('id', $profile->id)->update(['updated_at' => now()->subDays(400)]);

        $this->artisan('documents:prune', ['--days' => 365, '--force' => true])->assertSuccessful();

        Storage::disk('local')->assertMissing('private/cv/lama.pdf');
        $this->assertNull($profile->fresh()->cv_path);
    }
}

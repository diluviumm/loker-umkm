<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use App\Services\WhatsAppGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_persetujuan_mengirim_notifikasi_whatsapp_ke_pemilik(): void
    {
        $owner = User::factory()->pemilikUsaha()->create(['phone' => '6281234500011']);
        $category = Category::firstOrCreate(['slug' => 'kuliner'], ['nama' => 'Kuliner', 'icon' => 'fa-utensils']);

        $job = JobListing::create([
            'user_id'        => $owner->id,
            'category_id'    => $category->id,
            'nama_usaha'     => 'Kopi Uji',
            'posisi'         => 'Barista',
            'slug'           => 'barista-kopi-uji',
            'deskripsi'      => 'Melayani pelanggan dan membuat minuman kopi.',
            'lokasi'         => 'Bandung',
            'tipe_pekerjaan' => 'Full Time',
            'nomor_wa'       => '6281234500011',
            'status'         => 'pending',
            'is_premium'     => false,
            'premium_requested' => false,
        ]);

        $gateway = Mockery::mock(WhatsAppGateway::class);
        $gateway->shouldReceive('send')
            ->once()
            ->withArgs(fn ($phone, $message) => $phone === '6281234500011' && str_contains($message, 'DISETUJUI'));
        $this->app->instance(WhatsAppGateway::class, $gateway);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/admin/loker/{$job->id}/approve", ['is_premium' => '0'])
            ->assertRedirect();

        $this->assertSame('aktif', $job->fresh()->status);
    }

    public function test_penolakan_mengirim_notifikasi_whatsapp(): void
    {
        $owner = User::factory()->pemilikUsaha()->create(['phone' => '6281234500012']);
        $category = Category::firstOrCreate(['slug' => 'retail'], ['nama' => 'Retail', 'icon' => 'fa-store']);

        $job = JobListing::create([
            'user_id'        => $owner->id,
            'category_id'    => $category->id,
            'nama_usaha'     => 'Toko Uji',
            'posisi'         => 'Kasir',
            'slug'           => 'kasir-toko-uji',
            'deskripsi'      => 'Mengelola kasir dan menata barang di toko.',
            'lokasi'         => 'Bandung',
            'tipe_pekerjaan' => 'Full Time',
            'nomor_wa'       => '6281234500012',
            'status'         => 'pending',
        ]);

        $gateway = Mockery::mock(WhatsAppGateway::class);
        $gateway->shouldReceive('send')
            ->once()
            ->withArgs(fn ($phone, $message) => $phone === '6281234500012' && str_contains($message, 'DITOLAK'));
        $this->app->instance(WhatsAppGateway::class, $gateway);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/admin/loker/{$job->id}/reject")
            ->assertRedirect();

        $this->assertSame('ditolak', $job->fresh()->status);
    }
}

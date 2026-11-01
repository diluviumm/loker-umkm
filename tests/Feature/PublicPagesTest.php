<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function job(string $slug, string $status): JobListing
    {
        $category = Category::firstOrCreate(['slug' => 'kuliner'], ['nama' => 'Kuliner', 'icon' => 'fa-utensils']);

        return JobListing::create([
            'category_id'    => $category->id,
            'nama_usaha'     => 'Warung SEO',
            'posisi'         => 'Kasir',
            'slug'           => $slug,
            'deskripsi'      => 'Melayani pelanggan dan mengelola transaksi harian di warung.',
            'lokasi'         => 'Samarinda',
            'tipe_pekerjaan' => 'Full Time',
            'nomor_wa'       => '6281234567890',
            'status'         => $status,
            'approved_at'    => now(),
            'expires_at'     => now()->addDays(30),
        ]);
    }

    public function test_halaman_syarat_dan_privasi_tersedia(): void
    {
        $this->get('/syarat-ketentuan')->assertOk()->assertSee('Syarat & Ketentuan');
        $this->get('/kebijakan-privasi')->assertOk()->assertSee('Kebijakan Privasi');
    }

    public function test_sitemap_memuat_loker_aktif_saja(): void
    {
        $this->job('kasir-aktif-seo', 'aktif');
        $this->job('kasir-pending-seo', 'pending');

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $response->assertSee('kasir-aktif-seo', false);
        $response->assertDontSee('kasir-pending-seo', false);
    }

    public function test_meta_og_pada_halaman_loker(): void
    {
        $job = $this->job('kasir-og-seo', 'aktif');

        $this->get('/loker/' . $job->slug)
            ->assertOk()
            ->assertSee('og:image', false)
            ->assertSee('twitter:card', false)
            ->assertSee('name="description"', false);
    }

    public function test_halaman_legal_menautkan_kebijakan_di_form_daftar(): void
    {
        $this->get('/daftar/pelamar')->assertOk()->assertSee('/syarat-ketentuan', false)->assertSee('/kebijakan-privasi', false);
        $this->get('/daftar/usaha')->assertOk()->assertSee('/syarat-ketentuan', false)->assertSee('/kebijakan-privasi', false);
    }
}

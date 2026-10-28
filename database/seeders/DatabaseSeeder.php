<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->call(ProductionSeeder::class);

            return;
        }

        // 1. Create Default Super Admin
        User::updateOrCreate(
            ['email' => 'admin@loker.com'],
            [
                'name'     => 'Admin Utama',
                'email'    => 'admin@loker.com',
                'phone'    => '6281100000001',
                'password' => Hash::make('password123'),
                'role'     => 'super_admin',
            ]
        );

        // 2. Create Sample Pemilik Usaha
        $pemilikUser = User::updateOrCreate(
            ['email' => 'umkm@loker.com'],
            [
                'name'     => 'Sari Pemilik',
                'email'    => 'umkm@loker.com',
                'phone'    => '6281234567890',
                'password' => Hash::make('password123'),
                'role'     => 'pemilik_usaha',
            ]
        );

        // Profil pemilik usaha
        \App\Models\BusinessProfile::updateOrCreate(
            ['user_id' => $pemilikUser->id],
            [
                'nama_pemilik'     => 'Sari Pemilik',
                'nama_usaha'       => 'Kopi Sejahtera UMKM',
                'no_hp'            => '6281234567890',
                'alamat_usaha'     => 'Bandung, Jawa Barat',
                'kategori_usaha'   => 'Kuliner & F&B',
                'deskripsi_usaha'  => 'Kedai kopi kekinian yang menjual berbagai varian minuman kopi dan non-kopi.',
                'medsos_instagram' => '@kopisejahtera',
                'profil_lengkap'   => true,
            ]
        );

        // 2b. Akun pelamar demo
        $pelamar = User::updateOrCreate(
            ['email' => 'pelamar@loker.com'],
            [
                'name'     => 'Rina Pelamar',
                'email'    => 'pelamar@loker.com',
                'phone'    => '6285555555555',
                'password' => Hash::make('password123'),
                'role'     => 'pelamar',
            ]
        );

        // 3. Kategori (dipakai bersama oleh seeder demo & produksi)
        $this->call(CategorySeeder::class);

        $catFreelance = Category::where('slug', 'freelance')->first();
        $catGuru = Category::where('slug', 'guru-pendidikan')->first();
        $catIT = Category::where('slug', 'it-teknologi')->first();
        $catAdmin = Category::where('slug', 'admin-kasir')->first();
        $catAkuntansi = Category::where('slug', 'akuntansi-keuangan')->first();
        $catKuliner = Category::where('slug', 'kuliner-resto')->first();
        $catMarketing = Category::where('slug', 'marketing-medsos')->first();
        $catRetail = Category::where('slug', 'retail-toko')->first();

        // 4. Sample Job Listings di berbagai daerah Indonesia
        // Premium Active Job 1 - Bandung
        JobListing::updateOrCreate(
            ['slug' => 'barista-staff-kopi-sejahtera'],
            [
                'user_id'        => $pemilikUser->id,
                'category_id'    => $catKuliner->id,
                'nama_usaha'     => 'Kopi Sejahtera UMKM',
                'posisi'         => 'Barista & Staff Service',
                'deskripsi'      => "Dibutuhkan Barista ramah dan cekatan untuk Kedai Kopi Sejahtera.\n\nTugas:\n- Membuat minuman berbasis kopi.\n- Melayani pembeli dengan ramah.\n- Menjaga kebersihan area.",
                'syarat'         => "1. Pria/Wanita max 25 tahun\n2. Pendidikan min. SMA/SMK\n3. Jujur & komunikatif.",
                'lokasi'         => 'Bandung, Jawa Barat',
                'tipe_pekerjaan' => 'Full Time',
                'gaji'           => 'Rp 2.500.000 - Rp 3.200.000',
                'nomor_wa'       => '081234567890',
                'is_premium'     => true,
                'status'         => 'aktif',
                'approved_at'    => now()->subDays(2),
                'expires_at'     => now()->addDays(28),
            ]
        );

        // Premium Active Job 2 - IT Freelance Remote / Jakarta
        JobListing::updateOrCreate(
            ['slug' => 'web-developer-freelance-tokoku'],
            [
                'category_id'    => $catIT->id,
                'nama_usaha'     => 'Digital Creative Studio',
                'posisi'         => 'Web Developer (Laravel/Vue)',
                'deskripsi'      => "Mencari Web Developer freelance untuk pembuatan landing page dan sistem kasir online UMKM.",
                'syarat'         => "1. Menguasai PHP Laravel & HTML/CSS\n2. Punya portofolio website\n3. Siap kerja deadline.",
                'lokasi'         => 'Jakarta Selatan (Remote)',
                'tipe_pekerjaan' => 'Freelance',
                'gaji'           => 'Rp 3.500.000 - Rp 5.000.000 / Project',
                'nomor_wa'       => '081399887766',
                'is_premium'     => true,
                'status'         => 'aktif',
                'approved_at'    => now()->subDay(),
                'expires_at'     => now()->addDays(29),
            ]
        );

        // Guru Privat / Les - Yogyakarta
        JobListing::updateOrCreate(
            ['slug' => 'guru-les-privat-sd-smp-bimbel-cerdas'],
            [
                'category_id'    => $catGuru->id,
                'nama_usaha'     => 'Bimbel Cerdas Prestasi',
                'posisi'         => 'Guru Les Privat Matematika & Bahasa Inggris',
                'deskripsi'      => "Dibutuhkan Pengajar/Guru honorer atau mahasiswa untuk mengajar privat siswa SD & SMP.",
                'syarat'         => "1. Mahasiswa / Lulusan S1 Pendidikan\n2. Sabar dan menyukai anak-anak\n3. Domisili Jogja.",
                'lokasi'         => 'Yogyakarta',
                'tipe_pekerjaan' => 'Part Time',
                'gaji'           => 'Rp 1.500.000 - Rp 2.200.000',
                'nomor_wa'       => '085712345678',
                'is_premium'     => false,
                'status'         => 'aktif',
                'approved_at'    => now(),
                'expires_at'     => now()->addDays(30),
            ]
        );

        // Staff Akuntansi - Surabaya
        JobListing::updateOrCreate(
            ['slug' => 'staff-akuntansi-keuangan-toko-mas'],
            [
                'category_id'    => $catAkuntansi->id,
                'nama_usaha'     => 'Toko Emas & Perhiasan Jaya',
                'posisi'         => 'Staff Akuntansi & Pembukuan',
                'deskripsi'      => "Mencari staff akuntansi teliti untuk mengelola keuangan harian, laporan laba rugi, dan perpajakan dasar.",
                'syarat'         => "1. Lulusan D3/S1 Akuntansi\n2. Mahir Ms. Excel & software akuntansi\n3. Pengalaman min. 1 tahun.",
                'lokasi'         => 'Surabaya, Jawa Timur',
                'tipe_pekerjaan' => 'Full Time',
                'gaji'           => 'Rp 3.000.000 - Rp 4.000.000',
                'nomor_wa'       => '081987654321',
                'is_premium'     => false,
                'status'         => 'aktif',
                'approved_at'    => now()->subDays(1),
                'expires_at'     => now()->addDays(29),
            ]
        );

        // Admin & Kasir - Medan
        JobListing::updateOrCreate(
            ['slug' => 'kasir-admin-toko-baju-medan'],
            [
                'category_id'    => $catAdmin->id,
                'nama_usaha'     => 'Boutique Cantik Medan',
                'posisi'         => 'Kasir & Admin Toko',
                'deskripsi'      => "Lowongan Kasir toko fashion online & offline.",
                'syarat'         => "1. Wanita max 25 tahun\n2. Penampilan menarik & ramah\n3. Domisili Medan.",
                'lokasi'         => 'Medan, Sumatera Utara',
                'tipe_pekerjaan' => 'Full Time',
                'gaji'           => 'Rp 2.200.000',
                'nomor_wa'       => '082166554433',
                'is_premium'     => false,
                'status'         => 'aktif',
                'approved_at'    => now(),
                'expires_at'     => now()->addDays(30),
            ]
        );

        // Content Creator Freelance - Bali
        JobListing::updateOrCreate(
            ['slug' => 'freelance-videographer-bali'],
            [
                'category_id'    => $catFreelance->id,
                'nama_usaha'     => 'Bali Villa & Resto',
                'posisi'         => 'Freelance Content Creator & Designer',
                'deskripsi'      => "Membuat konten promosi berupa foto & video pendek untuk Instagram villa & cafe.",
                'syarat'         => "1. Punya HP/Kamera mendukung\n2. Kreatif & menguasai CapCut/Canva\n3. Domisili Bali.",
                'lokasi'         => 'Denpasar, Bali',
                'tipe_pekerjaan' => 'Freelance',
                'gaji'           => 'Rp 2.000.000 - Rp 3.500.000',
                'nomor_wa'       => '081299001122',
                'is_premium'     => false,
                'status'         => 'aktif',
                'approved_at'    => now(),
                'expires_at'     => now()->addDays(30),
            ]
        );

        // Pending Job
        JobListing::updateOrCreate(
            ['slug' => 'staff-gudang-distribusi-snack'],
            [
                'category_id'    => $catRetail->id,
                'nama_usaha'     => 'Snack Crispy Nusantara',
                'posisi'         => 'Staff Gudang & Packing',
                'deskripsi'      => 'Melakukan packing produk keripik dan penataan stok barang di gudang.',
                'syarat'         => 'Pria, fisik kuat, siap kerja shift.',
                'lokasi'         => 'Semarang, Jawa Tengah',
                'tipe_pekerjaan' => 'Full Time',
                'gaji'           => 'Rp 2.200.000',
                'nomor_wa'       => '087711223344',
                'is_premium'     => false,
                'status'         => 'pending',
                'approved_at'    => null,
                'expires_at'     => null,
            ]
        );

        // Beri umur berbeda pada data demo agar "x hari lalu" realistis.
        $demoAge = [
            'barista-staff-kopi-sejahtera'         => 3,
            'web-developer-freelance-tokoku'       => 2,
            'guru-les-privat-sd-smp-bimbel-cerdas' => 5,
            'staff-akuntansi-keuangan-toko-mas'    => 4,
            'kasir-admin-toko-baju-medan'          => 6,
            'freelance-videographer-bali'          => 7,
            'staff-gudang-distribusi-snack'        => 1,
        ];

        // Simpan beberapa loker contoh untuk akun pelamar demo
        $savedSlugs = ['barista-staff-kopi-sejahtera', 'web-developer-freelance-tokoku'];
        $savedIds = JobListing::whereIn('slug', $savedSlugs)->pluck('id');
        $pelamar->savedJobs()->syncWithoutDetaching($savedIds);

        foreach ($demoAge as $slug => $days) {
            JobListing::where('slug', $slug)->update([
                'created_at' => now()->subDays($days),
                'updated_at' => now()->subDays($days),
            ]);
        }
    }
}
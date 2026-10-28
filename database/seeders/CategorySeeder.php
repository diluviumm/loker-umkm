<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama' => 'Freelance', 'icon' => 'fa-laptop-code'],
            ['nama' => 'Guru & Pendidikan', 'icon' => 'fa-graduation-cap'],
            ['nama' => 'IT & Teknologi', 'icon' => 'fa-code'],
            ['nama' => 'Admin & Kasir', 'icon' => 'fa-cash-register'],
            ['nama' => 'Akuntansi & Keuangan', 'icon' => 'fa-calculator'],
            ['nama' => 'Kuliner & Resto', 'icon' => 'fa-utensils'],
            ['nama' => 'Retail & Toko', 'icon' => 'fa-store'],
            ['nama' => 'Marketing & Medsos', 'icon' => 'fa-bullhorn'],
            ['nama' => 'Driver & Kurir', 'icon' => 'fa-motorcycle'],
            ['nama' => 'Jasa & Kebersihan', 'icon' => 'fa-broom'],
            ['nama' => 'Teknik & Bengkel', 'icon' => 'fa-wrench'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['nama'])],
                ['nama' => $cat['nama'], 'icon' => $cat['icon']]
            );
        }
    }
}

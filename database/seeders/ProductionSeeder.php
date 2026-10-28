<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Seed minimal untuk produksi: kategori + satu super admin dari ENV.
     * Tidak membuat akun demo maupun loker contoh.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $email = config('app.admin_email');
        $password = config('app.admin_password');

        if (! $email || ! $password) {
            $this->command?->error('ADMIN_EMAIL dan ADMIN_PASSWORD belum diisi. Super admin tidak dibuat.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => config('app.admin_name'),
                'password' => Hash::make($password),
                'role'     => User::ROLE_ADMIN,
            ]
        );

        $this->command?->info("Super admin siap: {$email}");
    }
}

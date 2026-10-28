<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->unique()->after('email');
        });

        // Role ketiga (pelamar) tidak muat di enum lama; ubah ke string
        // dan validasi nilai role di level aplikasi.
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('pemilik_usaha')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};

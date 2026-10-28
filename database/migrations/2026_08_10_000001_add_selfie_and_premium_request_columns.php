<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            // Revisi verifikasi identitas: foto selfie sambil memegang KTP.
            $table->string('foto_selfie_ktp')->nullable()->after('foto_ktp');
        });

        Schema::table('job_listings', function (Blueprint $table) {
            // Penanda bahwa pemilik usaha MENGAJUKAN premium + sudah unggah bukti bayar.
            $table->boolean('premium_requested')->default(false)->after('is_premium');
        });
    }

    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropColumn('foto_selfie_ktp');
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn('premium_requested');
        });
    }
};

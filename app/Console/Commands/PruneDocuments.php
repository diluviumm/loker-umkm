<?php

namespace App\Console\Commands;

use App\Models\BusinessProfile;
use App\Models\DocumentPrune;
use App\Models\JobListing;
use App\Models\SeekerProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneDocuments extends Command
{
    protected $signature = 'documents:prune
                            {--days= : Umur minimal dokumen (hari)}
                            {--force : Hapus sungguhan (tanpa ini hanya pratinjau)}';

    protected $description = 'Hapus dokumen sensitif yang sudah tidak diperlukan (bukti bayar, KTP/selfie, CV).';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?: config('loker.retention.days', 365));
        $cutoff = now()->subDays($days);
        $force = (bool) $this->option('force');

        $targets = [];
        $push = function (string $type, ?string $path, string $reason) use (&$targets) {
            if (filled($path)) {
                $targets[] = ['type' => $type, 'path' => $path, 'reason' => $reason];
            }
        };

        // 1. Bukti pembayaran loker yang sudah final (diputus admin).
        JobListing::whereNotNull('bukti_pembayaran')
            ->whereIn('status', ['aktif', 'kadaluarsa', 'ditolak'])
            ->where('updated_at', '<', $cutoff)
            ->get()
            ->each(fn (JobListing $job) => $push(
                'payment_proof',
                $job->bukti_pembayaran,
                "loker #{$job->id} status {$job->status}"
            ));

        // 2. Identitas pemilik usaha yang sudah dimoderasi (punya loker aktif).
        BusinessProfile::where(function ($q) {
            $q->whereNotNull('foto_ktp')
                ->orWhereNotNull('foto_selfie_ktp')
                ->orWhereNotNull('foto_sim');
        })
            ->where('updated_at', '<', $cutoff)
            ->whereHas('user.jobListings', fn ($q) => $q->where('status', 'aktif'))
            ->get()
            ->each(function (BusinessProfile $profile) use ($push) {
                $push('identity', $profile->foto_ktp, "profil #{$profile->id} sudah dimoderasi");
                $push('identity', $profile->foto_selfie_ktp, "profil #{$profile->id} sudah dimoderasi");
                $push('identity', $profile->foto_sim, "profil #{$profile->id} sudah dimoderasi");
            });

        // 3. CV pelamar yang tidak diperbarui lagi.
        SeekerProfile::whereNotNull('cv_path')
            ->where('updated_at', '<', $cutoff)
            ->get()
            ->each(fn (SeekerProfile $profile) => $push(
                'cv',
                $profile->cv_path,
                "profil pelamar #{$profile->id} tidak diperbarui"
            ));

        if (! $targets) {
            $this->info("Tidak ada dokumen lebih tua dari {$days} hari yang memenuhi syarat.");

            return self::SUCCESS;
        }

        $this->table(
            ['Tipe', 'Path', 'Alasan'],
            array_map(fn ($t) => [$t['type'], $t['path'], $t['reason']], $targets)
        );

        if (! $force) {
            $this->warn('PRATINJAU saja. Jalankan ulang dengan --force untuk menghapus '.count($targets).' file.');

            return self::SUCCESS;
        }

        $deleted = 0;
        $bytes = 0;

        foreach ($targets as $target) {
            $disk = Storage::disk('local');

            if (! $disk->exists($target['path'])) {
                continue;
            }

            $bytes += $disk->size($target['path']);
            $disk->delete($target['path']);
            $deleted++;

            DocumentPrune::create([
                'type'   => $target['type'],
                'path'   => $target['path'],
                'reason' => $target['reason'],
                'bytes'  => $bytes,
            ]);
        }

        $this->clearReferences();

        $this->info("Selesai: {$deleted} file dihapus (".number_format($bytes / 1024, 1).' KB).');

        return self::SUCCESS;
    }

    /**
     * Kosongkan kolom yang menunjuk file yang sudah dihapus.
     */
    private function clearReferences(): void
    {
        $exists = fn (string $path) => Storage::disk('local')->exists($path);

        JobListing::whereNotNull('bukti_pembayaran')->get()->each(function (JobListing $job) use ($exists) {
            if (! $exists($job->bukti_pembayaran)) {
                $job->forceFill(['bukti_pembayaran' => null])->saveQuietly();
            }
        });

        BusinessProfile::all()->each(function (BusinessProfile $profile) use ($exists) {
            $dirty = [];
            foreach (['foto_ktp', 'foto_selfie_ktp', 'foto_sim'] as $column) {
                if ($profile->{$column} && ! $exists($profile->{$column})) {
                    $dirty[$column] = null;
                }
            }
            if ($dirty) {
                $profile->forceFill($dirty)->saveQuietly();
            }
        });

        SeekerProfile::whereNotNull('cv_path')->get()->each(function (SeekerProfile $profile) use ($exists) {
            if (! $exists($profile->cv_path)) {
                $profile->forceFill(['cv_path' => null])->saveQuietly();
            }
        });
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Sajikan dokumen privat (selfie+KTP, SIM, bukti pembayaran).
     * Hanya pemilik dokumen dan super admin yang boleh melihat.
     */
    public function show(string $path)
    {
        abort_if(str_contains($path, '..') || str_starts_with($path, '/'), 404);

        // Hanya folder dokumen yang boleh disajikan.
        abort_unless(
            str_starts_with($path, 'private/')
            || str_starts_with($path, 'dokumen/')
            || str_starts_with($path, 'pembayaran/'),
            404
        );

        $user = auth()->user();
        $profile = $user->businessProfile;

        $ownDocuments = array_filter([
            $profile?->foto_selfie_ktp,
            $profile?->foto_sim,
            $profile?->foto_ktp,
        ]);

        $allowed = $user->isSuperAdmin()
            || in_array($path, $ownDocuments, true)
            || $user->jobListings()->where('bukti_pembayaran', $path)->exists();

        abort_unless($allowed, 403);

        $headers = ['Cache-Control' => 'private, no-store, max-age=0'];

        if (Storage::disk('local')->exists($path)) {
            return response()->file(Storage::disk('local')->path($path), $headers);
        }

        // Kompatibilitas data lama yang tersimpan di disk publik.
        if (Storage::disk('public')->exists($path)) {
            return response()->file(Storage::disk('public')->path($path), $headers);
        }

        abort(404);
    }
}

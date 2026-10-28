<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

class JobTrackController extends Controller
{
    /**
     * Hitung klik tombol WhatsApp untuk analitik.
     * Hanya loker aktif yang dihitung, dan route dibatasi throttle.
     */
    public function trackClick(Request $request, JobListing $job)
    {
        if ($job->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Loker tidak aktif.',
            ], 422);
        }

        $job->increment('clicks_count');

        return response()->json([
            'success'      => true,
            'clicks_count' => $job->clicks_count,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\JobListing;

class JobDetailController extends Controller
{
    /**
     * Halaman detail loker.
     *
     * Hanya loker berstatus aktif & belum kedaluwarsa yang boleh tayang
     * untuk publik (loker pending/ditolak tidak boleh bocor lewat slug).
     */
    public function show($slug)
    {
        $job = JobListing::with('category')
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        $job->increment('views_count');

        // Rekomendasi loker serupa dari kategori yang sama.
        $relatedJobs = JobListing::aktif()
            ->where('id', '!=', $job->id)
            ->when($job->category_id, fn ($q) => $q->where('category_id', $job->category_id))
            ->limit(3)
            ->get();

        return view('jobs.show', compact('job', 'relatedJobs'));
    }
}

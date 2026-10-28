<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\JobListing;

class SavedJobController extends Controller
{
    public function store(JobListing $job)
    {
        if ($job->status !== 'aktif') {
            return back()->with('error', 'Loker ini tidak aktif, tidak bisa disimpan.');
        }

        auth()->user()->savedJobs()->syncWithoutDetaching([$job->id]);

        return back()->with('success', "Loker '{$job->posisi}' disimpan ke daftar Anda.");
    }

    public function destroy(JobListing $job)
    {
        auth()->user()->savedJobs()->detach($job->id);

        return back()->with('success', 'Loker dihapus dari daftar tersimpan.');
    }
}

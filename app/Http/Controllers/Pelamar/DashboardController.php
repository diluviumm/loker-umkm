<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $savedJobs = $user->savedJobs()
            ->with('category')
            ->orderByDesc('saved_jobs.created_at')
            ->paginate(9);

        return view('pelamar.dashboard', compact('user', 'savedJobs'));
    }
}

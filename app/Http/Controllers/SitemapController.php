<?php

namespace App\Http\Controllers;

use App\Models\JobListing;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $jobs = JobListing::aktif()
            ->latest('approved_at')
            ->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('jobs'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobListing;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = JobListing::aktif()->with('category');

        // Search Filter (Posisi / Nama Usaha / Deskripsi)
        if ($request->filled('q')) {
            // Escape wildcard LIKE agar '%' / '_' tidak mengubah pola pencarian.
            $search = addcslashes($request->string('q')->toString(), '%_\\');

            $query->where(function ($q) use ($search) {
                $q->where('posisi', 'like', "%{$search}%")
                  ->orWhere('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('kategori')) {
            $categorySlug = $request->string('kategori')->toString();
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Location Filter
        if ($request->filled('lokasi')) {
            $lokasi = addcslashes($request->string('lokasi')->toString(), '%_\\');
            $query->where('lokasi', 'like', "%{$lokasi}%");
        }

        $jobs = $query->paginate(12)->withQueryString();

        // Jumlah loker per kategori harus sama dengan yang benar-benar tayang.
        $categories = Category::withCount(['jobListings' => function ($q) {
            $q->where('status', 'aktif')
              ->where(function ($qq) {
                  $qq->whereNull('expires_at')
                     ->orWhere('expires_at', '>', now());
              });
        }])->orderBy('nama')->get();

        return view('home', compact('jobs', 'categories'));
    }
}

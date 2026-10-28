<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobSubmissionController extends Controller
{
    /**
     * Tampilkan formulir pengisian lowongan kerja (FASE 1)
     */
    public function create()
    {
        $categories = Category::all();
        return view('jobs.create', compact('categories'));
    }

    /**
     * Proses pengiriman lowongan oleh UMKM (FASE 1)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'required|string|min:20',
            'syarat' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string',
            'gaji' => 'nullable|string|max:255',
            'nomor_wa' => 'required|string|max:20',
        ], [
            'nama_usaha.required' => 'Nama Usaha / Toko / UMKM wajib diisi.',
            'posisi.required' => 'Posisi pekerjaan wajib diisi.',
            'category_id.required' => 'Pilih kategori pekerjaan.',
            'deskripsi.required' => 'Deskripsi pekerjaan minimal 20 karakter.',
            'lokasi.required' => 'Lokasi kota/kabupaten wajib diisi.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi untuk dihubungi pelamar.',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($validated['posisi'] . '-' . $validated['nama_usaha']);
        $slug = $baseSlug;
        $counter = 1;
        while (JobListing::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['status'] = 'pending'; // Otomatis Pending (FASE 1)
        $validated['is_premium'] = false; // Default False

        JobListing::create($validated);

        return redirect()->route('home')->with('success', 'Lowongan berhasil dikirim dan sedang menunggu persetujuan Admin.');
    }
}

<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user    = auth()->user();
        $profile = $user->businessProfile ?? new BusinessProfile();

        return view('business.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_pemilik'     => 'required|string|max:255',
            'nama_usaha'       => 'required|string|max:255',
            'no_hp'            => 'required|string|max:20',
            'alamat_usaha'     => 'nullable|string|max:500',
            'kategori_usaha'   => 'nullable|string|max:100',
            'deskripsi_usaha'  => 'nullable|string|max:1000',
            'medsos_instagram' => 'nullable|string|max:100',
            'medsos_tiktok'    => 'nullable|string|max:100',
            'medsos_facebook'  => 'nullable|string|max:100',
            'website'          => 'nullable|url|max:255',
            // Revisi: unggah KTP diganti foto selfie bersama KTP.
            'foto_selfie_ktp'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'foto_sim'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'nama_usaha.required'   => 'Nama usaha wajib diisi.',
            'no_hp.required'        => 'Nomor HP wajib diisi.',
            'foto_selfie_ktp.image' => 'File selfie + KTP harus berupa gambar.',
            'foto_selfie_ktp.mimes' => 'Format selfie + KTP harus JPG, PNG, atau WEBP.',
            'foto_selfie_ktp.max'   => 'Ukuran foto selfie + KTP maksimal 4MB.',
            'foto_sim.image'        => 'File SIM harus berupa gambar.',
            'foto_sim.mimes'        => 'Format file SIM harus JPG, PNG, atau WEBP.',
            'foto_sim.max'          => 'Ukuran file SIM maksimal 4MB.',
            'website.url'           => 'Format URL website tidak valid.',
        ]);

        $user = auth()->user();
        $data = $request->only([
            'nama_pemilik', 'nama_usaha', 'no_hp', 'alamat_usaha',
            'kategori_usaha', 'deskripsi_usaha', 'medsos_instagram',
            'medsos_tiktok', 'medsos_facebook', 'website',
        ]);

        $uploads = [
            'foto_selfie_ktp' => 'dokumen/selfie-ktp',
            'foto_sim'        => 'dokumen/sim',
        ];

        foreach ($uploads as $field => $folder) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $oldPath = $user->businessProfile?->{$field};

            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $data[$field] = $request->file($field)->store($folder, 'public');
        }

        // Profil dianggap lengkap bila data wajib terisi.
        $data['profil_lengkap'] = filled($data['nama_pemilik'] ?? null)
            && filled($data['nama_usaha'] ?? null)
            && filled($data['no_hp'] ?? null);

        $user->businessProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('business.dashboard')
            ->with('success', 'Profil usaha berhasil disimpan!');
    }
}

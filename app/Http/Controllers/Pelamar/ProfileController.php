<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\SeekerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->seekerProfile ?? new SeekerProfile();

        return view('pelamar.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'headline' => 'nullable|string|max:120',
            'city'     => 'nullable|string|max:100',
            'skills'   => 'nullable|string|max:1000',
            'cv'       => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'headline.max' => 'Headline maksimal 120 karakter.',
            'cv.mimes'     => 'CV harus berformat PDF.',
            'cv.max'       => 'Ukuran CV maksimal 5MB.',
        ]);

        $user = auth()->user();

        if ($request->hasFile('cv')) {
            if ($user->seekerProfile?->cv_path) {
                Storage::disk('local')->delete($user->seekerProfile->cv_path);
            }

            $data['cv_path'] = $request->file('cv')->store('private/cv', 'local');
        }

        unset($data['cv']);

        $user->seekerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('pelamar.dashboard')
            ->with('success', 'Profil pelamar berhasil disimpan.');
    }
}

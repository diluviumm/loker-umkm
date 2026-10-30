<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobPostController extends Controller
{
    public function create()
    {
        $user       = auth()->user();
        $profile    = $user->businessProfile;
        $categories = Category::orderBy('nama')->get();

        return view('business.post-job', compact('user', 'profile', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'posisi'           => 'required|string|max:255',
            'deskripsi'        => 'required|string|min:30',
            'syarat'           => 'nullable|string',
            'lokasi'           => 'required|string|in:' . implode(',', config('wilayah.kota')),
            'tipe_pekerjaan'   => 'required|in:Full Time,Part Time,Freelance,Magang',
            'gaji'             => 'nullable|string|max:100',
            'is_premium'       => 'nullable|boolean',
            'bukti_pembayaran' => 'nullable|required_if:is_premium,1|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'category_id.required'     => 'Kategori wajib dipilih.',
            'posisi.required'          => 'Posisi wajib diisi.',
            'deskripsi.required'       => 'Deskripsi pekerjaan wajib diisi.',
            'deskripsi.min'            => 'Deskripsi minimal 30 karakter.',
            'lokasi.required'          => 'Lokasi wajib dipilih.',
            'lokasi.in'                => 'Lokasi harus kota/kabupaten di Kalimantan Timur.',
            'tipe_pekerjaan.required'  => 'Tipe pekerjaan wajib dipilih.',
            'tipe_pekerjaan.in'        => 'Tipe pekerjaan tidak valid.',
            'bukti_pembayaran.required_if' => 'Bukti pembayaran wajib diunggah bila memilih paket Premium.',
            'bukti_pembayaran.image'   => 'Bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.mimes'   => 'Bukti pembayaran harus JPG, PNG, atau WEBP.',
            'bukti_pembayaran.max'     => 'Ukuran maksimal foto bukti adalah 4MB.',
        ]);

        $user    = auth()->user();
        $profile = $user->businessProfile;

        if (! $profile || ! $profile->profil_lengkap) {
            return redirect()->route('business.profile.edit')
                ->with('error', 'Harap lengkapi profil usaha Anda terlebih dahulu sebelum posting loker.');
        }

        $posisi = $request->string('posisi')->toString();
        $slug   = Str::slug($profile->nama_usaha . '-' . $posisi . '-' . Str::lower(Str::random(6)));

        $buktiPath = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('private/pembayaran', 'local');
        }

        $wantsPremium = $request->boolean('is_premium');

        JobListing::create([
            'user_id'           => $user->id,
            'category_id'       => $request->input('category_id'),
            'nama_usaha'        => $profile->nama_usaha,
            'posisi'            => $posisi,
            'slug'              => $slug,
            'deskripsi'         => $request->input('deskripsi'),
            'syarat'            => $request->input('syarat'),
            'lokasi'            => $request->input('lokasi'),
            'tipe_pekerjaan'    => $request->input('tipe_pekerjaan'),
            'gaji'              => $request->input('gaji'),
            'nomor_wa'          => $profile->no_hp,
            'status'            => 'pending',
            // Premium baru aktif setelah admin menyetujui (lihat AdminDashboardController@approve).
            'is_premium'        => false,
            'premium_requested' => $wantsPremium,
            'bukti_pembayaran'  => $buktiPath,
        ]);

        return redirect()->route('business.dashboard')
            ->with('success', "Loker '{$posisi}' berhasil dikirim dan menunggu persetujuan admin.");
    }

    public function destroy(JobListing $job)
    {
        if ((int) $job->user_id !== (int) auth()->id()) {
            abort(403, 'Anda bukan pemilik loker ini.');
        }

        if (! in_array($job->status, ['pending', 'ditolak'], true)) {
            return back()->with('error', 'Loker yang aktif atau kadaluarsa tidak dapat dihapus. Hubungi admin.');
        }

        $job->delete();

        return back()->with('success', 'Loker berhasil dihapus.');
    }

    public function renew(JobListing $job)
    {
        if ((int) $job->user_id !== (int) auth()->id()) {
            abort(403, 'Anda bukan pemilik loker ini.');
        }

        if (! in_array($job->status, ['kadaluarsa', 'ditolak'], true)) {
            return back()->with('error', 'Hanya loker kadaluarsa/ditolak yang bisa diajukan perpanjangan.');
        }

        $job->update([
            'status'      => 'pending',
            'approved_at' => null,
            'expires_at'  => null,
        ]);

        return back()->with('success', 'Permintaan perpanjangan loker berhasil dikirim ke Admin.');
    }

    public function close(JobListing $job)
    {
        if ((int) $job->user_id !== (int) auth()->id()) {
            abort(403, 'Anda bukan pemilik loker ini.');
        }

        if ($job->status !== 'aktif') {
            return back()->with('error', 'Hanya loker aktif yang bisa ditutup.');
        }

        $job->update(['status' => 'kadaluarsa']);

        return back()->with('success', 'Loker berhasil ditandai sebagai sudah terisi / ditutup.');
    }
}

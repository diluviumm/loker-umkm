<?php

namespace App\Http\Controllers;

use App\Mail\JobStatusMail;
use App\Models\JobListing;
use App\Models\User;
use App\Services\WhatsAppGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminDashboardController extends Controller
{
    public function __construct(private WhatsAppGateway $whatsapp)
    {
    }

    /**
     * Tampilkan form login Admin.
     */
    public function loginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('admin.login');
    }

    /**
     * Proses login Admin.
     *
     * Catatan keamanan: tidak ada lagi "auto-fix password" saat hash korup.
     * Perilaku lama memungkinkan siapa pun menimpa password akun mana pun
     * hanya dengan memicu RuntimeException dari Hash::check().
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email admin wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (! Auth::user()->isSuperAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akses ditolak. Halaman ini khusus Admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang kembali, Admin!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout Admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Berhasil keluar dari sesi Admin.');
    }

    /**
     * Dashboard moderasi: daftar loker + statistik.
     */
    public function dashboard(Request $request)
    {
        $statusFilter = $request->input('status', 'pending');
        $allowed = ['pending', 'aktif', 'ditolak', 'kadaluarsa'];

        $stats = [
            'total'      => JobListing::count(),
            'pending'    => JobListing::where('status', 'pending')->count(),
            'aktif'      => JobListing::where('status', 'aktif')->count(),
            'ditolak'    => JobListing::where('status', 'ditolak')->count(),
            'kadaluarsa' => JobListing::where('status', 'kadaluarsa')->count(),
            'premium'    => JobListing::where('is_premium', true)->count(),
        ];

        $query = JobListing::with(['category', 'user.businessProfile'])->latest();

        if (in_array($statusFilter, $allowed, true)) {
            $query->where('status', $statusFilter);
        }

        $jobs = $query->paginate(15)->withQueryString();

        return view('admin.dashboard', compact('jobs', 'stats', 'statusFilter'));
    }

    /**
     * Setujui loker.
     *
     * Revisi PR Premium: status premium ditentukan oleh bukti pembayaran.
     * - Sudah bayar (pengajuan premium + bukti ada) → wajib Premium.
     * - Belum bayar → wajib Gratis (tombol premium tidak tersedia di UI,
     *   dan parameter is_premium dari request diabaikan/ditolak).
     */
    /**
     * Traffic pendaftar: siapa yang mendaftar sebagai pelamar dan pemilik usaha.
     */
    public function users(Request $request)
    {
        $role = (string) $request->input('role', '');
        $q = trim((string) $request->input('q', ''));
        $allowed = ['pelamar', 'pemilik_usaha', 'super_admin'];

        $users = User::query()
            ->when(in_array($role, $allowed, true), fn ($query) => $query->where('role', $role))
            ->when($q !== '', function ($query) use ($q) {
                $needle = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(function ($sub) use ($needle) {
                    $sub->where('name', 'like', $needle)
                        ->orWhere('email', 'like', $needle)
                        ->orWhere('phone', 'like', $needle);
                });
            })
            ->withCount(['jobListings', 'savedJobs'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'pelamar'         => User::where('role', 'pelamar')->count(),
            'pemilik'         => User::where('role', 'pemilik_usaha')->count(),
            'pelamar_baru'    => User::where('role', 'pelamar')->where('created_at', '>=', now()->subDays(7))->count(),
            'pemilik_baru'    => User::where('role', 'pemilik_usaha')->where('created_at', '>=', now()->subDays(7))->count(),
            'wa_terverifikasi' => User::whereNotNull('phone_verified_at')->count(),
            'wa_belum'        => User::where('role', 'pemilik_usaha')->whereNull('phone_verified_at')->count(),
            'total_views'     => (int) JobListing::sum('views_count'),
            'total_clicks'    => (int) JobListing::sum('clicks_count'),
        ];

        return view('admin.users', compact('users', 'stats', 'role', 'q'));
    }

    public function approve(Request $request, JobListing $job)
    {
        $paidPremium = $job->hasPaidPremium();
        $isReactivation = $job->status !== 'pending';

        // Aturan pemilihan jalur hanya berlaku saat menyetujui pengajuan baru.
        if (! $isReactivation) {
            if ($request->boolean('is_premium') && ! $paidPremium) {
                return back()->with('error', 'Loker ini belum punya bukti pembayaran premium yang valid. Setujui sebagai Gratis, atau minta pemilik mengunggah bukti pembayaran.');
            }

            if (! $request->boolean('is_premium') && $paidPremium) {
                return back()->with('error', 'Loker ini sudah dibayar sebagai Premium — persetujuan gratis dinonaktifkan. Gunakan tombol Setujui Premium.');
            }
        }

        $job->update([
            'status'      => 'aktif',
            'is_premium'  => $paidPremium,
            'approved_at' => now(),
            'expires_at'  => now()->addDays(30),
        ]);

        $this->notifyOwner($job, $paidPremium ? 'approved_premium' : 'approved_gratis');

        $statusText = $paidPremium ? 'Aktif (PREMIUM)' : 'Aktif (Gratis)';

        return back()->with('success', "Lowongan '{$job->posisi}' di '{$job->nama_usaha}' berhasil disetujui dengan status: {$statusText}.");
    }

    /**
     * Tolak loker.
     */
    public function reject(JobListing $job)
    {
        $job->update(['status' => 'ditolak']);

        $this->notifyOwner($job, 'rejected');

        return back()->with('success', "Lowongan '{$job->posisi}' di '{$job->nama_usaha}' telah ditolak.");
    }

    /**
     * Toggle fitur premium pada loker yang sudah aktif.
     */
    public function togglePremium(JobListing $job)
    {
        $job->update([
            'is_premium' => ! $job->is_premium,
        ]);

        $statusStr = $job->is_premium
            ? 'diaktifkan sebagai Premium'
            : 'diubah menjadi Gratis';

        return back()->with('success', "Status premium loker '{$job->posisi}' berhasil {$statusStr}.");
    }

    /**
     * Hapus loker dari database.
     */
    public function destroy(JobListing $job)
    {
        $job->delete();

        return back()->with('success', 'Data lowongan kerja berhasil dihapus.');
    }

    /**
     * Kirim email status ke pemilik usaha tanpa membuat request 500
     * ketika SMTP belum dikonfigurasi.
     */
    private function notifyOwner(JobListing $job, string $status): void
    {
        $email = $job->user?->email;

        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new JobStatusMail($job, $status));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email status loker', [
                'job_id' => $job->id,
                'email'  => $email,
                'error'  => $e->getMessage(),
            ]);
        }

        // Notifikasi WhatsApp (opsional, memakai Fonnte bila token tersedia).
        $phone = $job->user?->phone ?? $job->user?->businessProfile?->no_hp;

        if ($phone) {
            $this->whatsapp->send($phone, $this->statusMessage($job, $status));
        }
    }

    private function statusMessage(JobListing $job, string $status): string
    {
        $url = route('jobs.show', $job->slug);

        return match ($status) {
            'approved_premium' => "Halo {$job->user?->name}, loker *{$job->posisi}* di {$job->nama_usaha} sudah DISETUJUI sebagai PREMIUM dan tayang 30 hari. Lihat: {$url}",
            'approved_gratis'  => "Halo {$job->user?->name}, loker *{$job->posisi}* di {$job->nama_usaha} sudah DISETUJUI dan tayang 30 hari. Lihat: {$url}",
            default            => "Halo {$job->user?->name}, mohon maaf loker *{$job->posisi}* di {$job->nama_usaha} DITOLAK oleh admin. Silakan perbaiki dan ajukan ulang.",
        };
    }

    private function redirectByRole()
    {
        return Auth::user()->isSuperAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('business.dashboard');
    }
}

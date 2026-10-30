<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppGateway;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private WhatsAppOtpService $otp,
        private WhatsAppGateway $gateway
    ) {
    }

    // ─── Login ───────────────────────────────────────────────────────
    public function loginForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole(auth()->user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    // ─── Daftar: pilih jenis akun ────────────────────────────────────
    public function chooseForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.choose');
    }

    // ─── Daftar pelamar (email + password saja) ──────────────────────
    public function pelamarForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.register-pelamar');
    }

    public function registerPelamar(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'terms'                 => 'accepted',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar, silakan masuk.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted'     => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => null,
            'password' => Hash::make($data['password']),
            'role'     => 'pelamar',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pelamar.dashboard')
            ->with('success', 'Akun pelamar berhasil dibuat. Sekarang Anda bisa melihat kontak WhatsApp dan menyimpan loker.');
    }

    // ─── Daftar pemilik usaha (WA wajib, verifikasi Fonnte) ──────────
    public function usahaForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.register-usaha', [
            'otpReady' => $this->gateway->isConfigured(),
        ]);
    }

    public function registerUsaha(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users,email',
            'phone'                 => 'required|string|min:9|max:20',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'terms'                 => 'accepted',
        ], [
            'name.required'      => 'Nama pemilik usaha wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar, silakan masuk.',
            'phone.required'     => 'Nomor WhatsApp wajib diisi.',
            'phone.min'          => 'Nomor WhatsApp tidak valid.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted'     => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        $phone = $this->otp->normalize($data['phone']);

        if (User::where('phone', $phone)->exists()) {
            return back()->withInput()->withErrors([
                'phone' => 'Nomor WhatsApp ini sudah dipakai akun lain.',
            ]);
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $phone,
            'password' => Hash::make($data['password']),
            'role'     => 'pemilik_usaha',
        ]);

        // Verifikasi WA wajib bila Fonnte aktif; tanpa token, akun tetap dibuat
        // dan admin bisa memverifikasi manual saat menelepon/meninjau.
        if ($this->gateway->isConfigured()) {
            $result = $this->otp->request($phone);

            if ($result['sent']) {
                $request->session()->put('pending_owner_id', $user->id);

                return redirect()->route('register.usaha.verify')
                    ->with('success', 'Kode verifikasi sudah dikirim ke WhatsApp ' . $phone . '.');
            }

            if ($result['throttled']) {
                $request->session()->put('pending_owner_id', $user->id);

                return redirect()->route('register.usaha.verify')
                    ->with('error', 'Kode sudah dikirim baru saja. Tunggu 60 detik sebelum minta kode baru.');
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('business.profile.edit')
            ->with('success', 'Akun pemilik usaha berhasil dibuat. Lengkapi profil usaha Anda.')
            ->with('info', $this->gateway->isConfigured()
                ? 'Verifikasi WhatsApp belum selesai. Hubungi admin bila kode tidak diterima.'
                : 'Verifikasi WhatsApp otomatis belum aktif (token Fonnte kosong). Admin akan memverifikasi nomor Anda secara manual.');
    }

    public function usahaVerifyForm(Request $request)
    {
        $user = User::find($request->session()->get('pending_owner_id'));

        if (! $user) {
            return redirect()->route('register.usaha')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan daftar ulang atau masuk.');
        }

        return view('auth.register-usaha-verify', [
            'phone' => $user->phone,
        ]);
    }

    public function usahaVerify(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.digits'   => 'Kode verifikasi harus 6 angka.',
        ]);

        $user = User::find($request->session()->get('pending_owner_id'));

        if (! $user) {
            return redirect()->route('register.usaha')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan masuk kembali.');
        }

        $verified = $this->otp->verify($user->phone, $data['code']);

        if (! $verified) {
            return back()->withErrors([
                'code' => 'Kode salah atau sudah kedaluwarsa. Minta kode baru bila perlu.',
            ]);
        }

        $user->forceFill(['phone_verified_at' => now()])->save();
        $request->session()->forget('pending_owner_id');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('business.profile.edit')
            ->with('success', 'Nomor WhatsApp terverifikasi. Lengkapi profil usaha Anda.');
    }

    public function resendUsahaOtp(Request $request)
    {
        $user = User::find($request->session()->get('pending_owner_id'));

        if (! $user) {
            return redirect()->route('register.usaha')->with('error', 'Sesi verifikasi tidak ditemukan.');
        }

        $result = $this->otp->request($user->phone);

        if ($result['sent']) {
            return back()->with('success', 'Kode baru sudah dikirim ke WhatsApp Anda.');
        }

        return back()->with('error', 'Kode belum bisa dikirim. Coba lagi dalam 60 detik.');
    }

    // ─── Logout ──────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil logout.');
    }

    // ─── Helper ──────────────────────────────────────────────────────
    private function redirectByRole(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isPelamar()) {
            return redirect()->route('pelamar.dashboard');
        }

        return redirect()->route('business.dashboard');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private WhatsAppOtpService $otp)
    {
    }

    // ─── Login Form ──────────────────────────────────────────────────
    public function loginForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.login');
    }

    // ─── Proses Login ────────────────────────────────────────────────
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

    // ─── Register Form ───────────────────────────────────────────────
    public function registerForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.register');
    }

    // ─── Proses Register (Pemilik Usaha / Pelamar) ───────────────────
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|string|max:20',
            'role'                  => 'required|in:pemilik_usaha,pelamar',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar, silakan login.',
            'phone.required'     => 'Nomor WhatsApp wajib diisi.',
            'role.required'      => 'Pilih jenis akun.',
            'role.in'            => 'Jenis akun tidak valid.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
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
            'role'     => $data['role'],
        ]);

        Auth::login($user);

        if ($user->isPelamar()) {
            return redirect()->route('pelamar.dashboard')
                ->with('success', 'Akun pelamar berhasil dibuat. Simpan loker favoritmu di sini!');
        }

        return redirect()->route('business.profile.edit')
            ->with('success', 'Akun berhasil dibuat! Silakan lengkapi profil usaha Anda terlebih dahulu.');
    }

    // ─── Logout ──────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil logout.');
    }

    // ─── Helper: Redirect By Role ─────────────────────────────────────
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

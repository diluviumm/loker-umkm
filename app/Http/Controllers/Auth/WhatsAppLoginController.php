<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppLoginController extends Controller
{
    public function __construct(private WhatsAppOtpService $otp)
    {
    }

    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login-wa');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string|max:20',
        ], [
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        $result = $this->otp->request($data['phone']);

        if (! $result['user']) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Nomor ini belum terdaftar. Silakan daftar terlebih dahulu.']);
        }

        if ($result['throttled']) {
            return back()->withInput()->withErrors(['phone' => 'Kode sudah dikirim. Tunggu 60 detik sebelum meminta lagi.']);
        }

        $request->session()->put('otp_phone', $result['phone']);

        $message = $result['sent']
            ? 'Kode OTP sudah dikirim ke WhatsApp Anda.'
            : 'Fonnte belum dikonfigurasi, jadi kode OTP dicatat di log server.';

        // Mode demo lokal: tampilkan kode agar alur bisa diuji tanpa Fonnte.
        if (! $result['sent'] && app()->environment('local') && $result['code']) {
            $message .= ' Kode demo: ' . $result['code'];
        }

        return back()->with('success', $message);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Kode OTP wajib diisi.',
            'code.digits'   => 'Kode OTP terdiri dari 6 angka.',
        ]);

        $phone = $request->session()->get('otp_phone');

        if (! $phone) {
            return redirect()->route('login.wa')->withErrors(['phone' => 'Sesi OTP tidak ditemukan. Mulai ulang.']);
        }

        $user = $this->otp->verify($phone, $data['code']);

        if (! $user) {
            return back()->withErrors(['code' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        Auth::login($user, true);
        $request->session()->regenerate();
        $request->session()->forget('otp_phone');

        return $this->redirectByRole()->with('success', 'Berhasil masuk dengan WhatsApp.');
    }

    private function redirectByRole()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isPelamar()) {
            return redirect()->route('pelamar.dashboard');
        }

        return redirect()->route('business.dashboard');
    }
}

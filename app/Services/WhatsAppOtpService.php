<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WhatsAppOtpService
{
    public function __construct(private WhatsAppGateway $gateway)
    {
    }

    /**
     * Normalisasi nomor ke format 62xxxxxxxxxx.
     */
    public function normalize(string $phone): string
    {
        return $this->gateway->normalize($phone);
    }

    /**
     * Buat & kirim kode OTP. Mengembalikan status untuk ditampilkan ke user.
     *
     * @return array{user: ?User, phone: string, sent: bool, throttled: bool, code: ?string}
     */
    public function request(string $phone): array
    {
        $normalized = $this->normalize($phone);

        // Cocokkan 9 digit terakhir agar data lama berformat 08xxx tetap dikenali.
        $tail = substr($normalized, -9);

        $user = User::where('phone', $normalized)->first()
            ?? User::where('phone', 'like', '%' . $tail)->first()
            ?? User::whereHas('businessProfile', fn ($q) => $q->where('no_hp', 'like', '%' . $tail))->first();

        if (! $user) {
            return ['user' => null, 'phone' => $normalized, 'sent' => false, 'throttled' => false, 'code' => null];
        }

        // Satu kode per 60 detik per nomor.
        $recent = OtpCode::where('phone', $normalized)
            ->where('created_at', '>', now()->subSeconds(60))
            ->exists();

        if ($recent) {
            return ['user' => $user, 'phone' => $normalized, 'sent' => false, 'throttled' => true, 'code' => null];
        }

        // Housekeeping: buang kode yang sudah lama kedaluwarsa.
        OtpCode::where('expires_at', '<', now()->subDay())->delete();

        $code = (string) random_int(100000, 999999);

        OtpCode::create([
            'phone'      => $normalized,
            'code_hash'  => Hash::make($code),
            'expires_at' => now()->addMinutes(5),
            'ip'         => request()->ip(),
        ]);

        $message = "Kode OTP Info Loker UMKM: {$code}. Berlaku 5 menit. Jangan bagikan kode ini kepada siapa pun.";
        $sent = $this->gateway->send($normalized, $message);

        return ['user' => $user, 'phone' => $normalized, 'sent' => $sent, 'throttled' => false, 'code' => $code];
    }

    /**
     * Verifikasi kode. Mengembalikan user bila valid.
     */
    public function verify(string $phone, string $code): ?User
    {
        $normalized = $this->normalize($phone);

        $otp = OtpCode::where('phone', $normalized)->latest('id')->first();

        if (! $otp || ! $otp->isUsable()) {
            return null;
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');

            return null;
        }

        $otp->update(['consumed_at' => now()]);

        return User::where('phone', $normalized)->first();
    }

}

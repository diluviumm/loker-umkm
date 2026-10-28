<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppOtpService
{
    /**
     * Normalisasi nomor ke format 62xxxxxxxxxx.
     */
    public function normalize(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62' . $digits;
        }

        return $digits;
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

        $sent = $this->send($normalized, $code);

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

    /**
     * Kirim via Fonnte; bila token belum diisi, kode dicatat ke log (driver log).
     */
    private function send(string $phone, string $code): bool
    {
        $token = config('services.fonnte.token');
        $message = "Kode OTP Info Loker UMKM: {$code}. Berlaku 5 menit. Jangan bagikan kode ini kepada siapa pun.";

        if (! $token) {
            if (app()->environment('local', 'testing')) {
                // Hanya di lokal: kode ditulis agar alur bisa diuji tanpa Fonnte.
                Log::info('OTP driver log (FONNTE_TOKEN belum diisi).', [
                    'phone' => $phone,
                    'code'  => $code,
                ]);
            } else {
                Log::warning('FONNTE_TOKEN belum diisi — OTP tidak terkirim.', [
                    'phone' => $phone,
                ]);
            }

            return false;
        }

        try {
            $response = Http::withHeaders(['Authorization' => $token])
                ->asForm()
                ->timeout(10)
                ->post(config('services.fonnte.url'), [
                    'target'  => $phone,
                    'message' => $message,
                ]);

            if (! $response->successful()) {
                Log::warning('Fonnte menolak permintaan OTP', [
                    'phone'  => $phone,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim OTP via Fonnte', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

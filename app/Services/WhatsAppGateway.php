<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppGateway
{
    public function isConfigured(): bool
    {
        return filled(config('services.fonnte.token'));
    }

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
     * Kirim pesan WhatsApp. Tanpa token Fonnte, pesan hanya dicatat ke log
     * pada environment local/testing (tidak pernah di produksi).
     */
    public function send(string $phone, string $message): bool
    {
        $target = $this->normalize($phone);

        if (! $this->isConfigured()) {
            if (app()->environment('local', 'testing')) {
                Log::info('WhatsApp (driver log): pesan tidak benar-benar dikirim.', [
                    'phone'   => $target,
                    'message' => $message,
                ]);
            } else {
                Log::warning('FONNTE_TOKEN belum diisi — pesan WhatsApp tidak terkirim.', [
                    'phone' => $target,
                ]);
            }

            return false;
        }

        try {
            $response = Http::withHeaders(['Authorization' => config('services.fonnte.token')])
                ->asForm()
                ->timeout(10)
                ->post(config('services.fonnte.url'), [
                    'target'  => $target,
                    'message' => $message,
                ]);

            if (! $response->successful()) {
                Log::warning('Fonnte menolak pengiriman pesan', [
                    'phone'  => $target,
                    'status' => $response->status(),
                ]);
            }

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim WhatsApp', [
                'phone' => $target,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

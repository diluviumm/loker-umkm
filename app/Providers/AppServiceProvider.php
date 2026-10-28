<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Agar diffForHumans() mengikuti bahasa aplikasi (id/en).
        Carbon::setLocale(config('app.locale'));

        // Di balik Cloudflare Tunnel, paksa URL https agar aset tidak mixed-content.
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}

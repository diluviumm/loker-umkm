<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
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
    }
}

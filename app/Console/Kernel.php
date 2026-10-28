<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('loker:check-expired')
            ->daily()
            ->withoutOverlapping();

        // Retensi dokumen: pratinjau bulanan; hapus hanya bila ENFORCE=true.
        $prune = 'documents:prune'.(config('loker.retention.enforce') ? ' --force' : '');

        $schedule->command($prune)
            ->monthlyOn(1, '03:15')
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

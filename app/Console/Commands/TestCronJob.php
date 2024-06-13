<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCronJob extends Command
{
    protected $signature = 'test:cronjob';
    protected $description = 'Test if the cron job is working';

    public function handle()
    {
        Log::info('Cron job executed successfully at ' . now());
        $this->info('Cron job executed successfully.');
        return 0;
    }
}

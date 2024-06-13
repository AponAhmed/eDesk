<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class resentDailySendCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:dailysendcount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset daily Send Count  everyday';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        DB::table('senders')->update(['daily_send_count' => 0]);
        $this->info('Daily send count reset successfully.');
        Log::info('Cron:: Daily send count reset successfully at ' . now());
        return 0;
    }
}

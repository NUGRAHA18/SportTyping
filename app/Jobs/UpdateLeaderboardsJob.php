<?php

namespace App\Jobs;

use App\Console\Commands\UpdateLeaderboards;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateLeaderboardsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Clear leaderboard caches before update
        Cache::tags(['leaderboards'])->flush();
        
        // Run leaderboard update
        (new UpdateLeaderboards())->handle();
        
        // Cache will be regenerated on next request
    }
}

<?php

namespace App\Console\Commands;

use App\Services\CompetitionTimerService;
use Illuminate\Console\Command;

class CheckCompetitionStatus extends Command
{
    protected $signature = 'competition:check-status';
    protected $description = 'Check and update competition statuses';

    public function handle(CompetitionTimerService $timerService)
    {
        $this->info('Checking competition statuses...');
        
        $updated = $timerService->checkAndUpdateCompetitionStatuses();
        
        $this->info("Started competitions: {$updated['started']}");
        $this->info("Ended competitions: {$updated['ended']}");
        
        return Command::SUCCESS;
    }
}
<?php
namespace App\Services;

use App\Models\Competition;
use App\Events\CompetitionStarted;
use App\Events\CompetitionEnded;
use Carbon\Carbon;

class CompetitionTimerService
{
    public function checkAndUpdateCompetitionStatuses(): array
    {
        $updated = [
            'started' => 0,
            'ended' => 0
        ];

        // Start scheduled competitions
        $toStart = Competition::where('status', 'upcoming')
            ->where('start_time', '<=', now())
            ->get();

        foreach ($toStart as $competition) {
            $competition->update(['status' => 'active']);
            event(new CompetitionStarted($competition));
            $updated['started']++;
        }

        // End expired competitions
        $toEnd = Competition::where('status', 'active')
            ->where('end_time', '<', now())
            ->get();

        foreach ($toEnd as $competition) {
            $competition->update(['status' => 'completed']);
            event(new CompetitionEnded($competition));
            $updated['ended']++;
        }

        return $updated;
    }

    public function getRemainingTime(Competition $competition): int
    {
        if ($competition->status !== 'active') {
            return 0;
        }

        return max(0, $competition->end_time->diffInSeconds(now()));
    }

    public function getTimeUntilStart(Competition $competition): int
    {
        if ($competition->status !== 'upcoming') {
            return 0;
        }

        return max(0, $competition->start_time->diffInSeconds(now()));
    }

    public function isCompetitionActive(Competition $competition): bool
    {
        return $competition->status === 'active' && 
               now()->between($competition->start_time, $competition->end_time);
    }
}
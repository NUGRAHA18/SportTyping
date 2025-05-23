<?php

namespace App\Jobs;

use App\Models\CompetitionResult;
use App\Models\User;
use App\Services\BadgeService;
use App\Services\LeagueService;
use App\Services\TypingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessCompetitionResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $competitionResult;

    public function __construct(CompetitionResult $competitionResult)
    {
        $this->competitionResult = $competitionResult;
    }

    public function handle(BadgeService $badgeService, LeagueService $leagueService, TypingService $typingService)
    {
        try {
            $user = $this->competitionResult->user;

            // Update user typing statistics
            $typingService->updateUserTypingStats($user);

            // Check for badges
            $badgeService->checkAndAwardSpeedBadges($user, $this->competitionResult->typing_speed);
            $badgeService->checkAndAwardAccuracyBadges($user, $this->competitionResult->typing_accuracy);
            $badgeService->checkAndAwardCompetitionBadges($user);

            // Check league progression
            $leagueService->updateUserLeague($user);

            // Clear relevant caches
            $this->clearUserCaches($user);

            Log::info('Competition result processed successfully', [
                'user_id' => $user->id,
                'competition_id' => $this->competitionResult->competition_id,
                'wpm' => $this->competitionResult->typing_speed
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process competition result', [
                'error' => $e->getMessage(),
                'competition_result_id' => $this->competitionResult->id
            ]);
            
            $this->fail($e);
        }
    }

    private function clearUserCaches(User $user): void
    {
        $cacheKeys = [
            "user.{$user->id}.dashboard",
            "user.{$user->id}.profile",
            "user.{$user->id}.recent_competitions",
            "user.{$user->id}.badges",
            "leaderboard.global",
            "leaderboard.league.{$user->profile->current_league_id}",
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}

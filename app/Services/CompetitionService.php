<?php
namespace App\Services;

use App\Models\Competition;
use App\Models\User;
use App\Events\CompetitionStarted;
use App\Events\CompetitionProgress;
use App\Events\CompetitionEnded;
use App\Events\CompetitionCountdown;

class CompetitionService
{
    protected $botService;
    protected $timerService;

    public function __construct(BotService $botService, CompetitionTimerService $timerService)
    {
        $this->botService = $botService;
        $this->timerService = $timerService;
    }
    
    public function updateCompetitionProgress(Competition $competition, User $user, array $progressData): array
    {
        // Validate competition is active
        if (!$this->timerService->isCompetitionActive($competition)) {
            throw new \Exception('Competition is not active');
        }

        // Calculate position among all participants
        $position = $this->calculateUserPosition($competition, $user, $progressData['progress']);
        $progressData['position'] = $position;

        // Broadcast progress update
        event(new CompetitionProgress($competition, $user, $progressData));

        // If user finished, check if competition should end
        if ($progressData['progress'] >= 100) {
            $this->checkCompetitionCompletion($competition);
        }

        return $progressData;
    }

    private function calculateUserPosition(Competition $competition, User $user, int $progress): int
    {
        // This would normally query current race state from cache/database
        // For now, return a mock position
        return rand(1, $competition->participants()->count());
    }

    private function checkCompetitionCompletion(Competition $competition): void
    {
        // Check if all human participants have finished
        $totalParticipants = $competition->participants()->where('is_bot', false)->count();
        $finishedParticipants = $competition->results()->count();

        if ($finishedParticipants >= $totalParticipants) {
            $competition->update(['status' => 'completed']);
            event(new CompetitionEnded($competition));
        }
    }

    public function broadcastCountdown(Competition $competition): void
    {
        $remainingTime = $this->timerService->getRemainingTime($competition);
        event(new CompetitionCountdown($competition, $remainingTime));
    }

    public function getCompetitionParticipants(Competition $competition): array
    {
        $humanParticipants = $competition->participants()
            ->with('user.profile')
            ->where('is_bot', false)
            ->get()
            ->map(function ($participant) {
                return [
                    'id' => $participant->user->id,
                    'username' => $participant->user->username,
                    'avatar' => $participant->user->profile->avatar ?? null,
                    'league' => $participant->user->profile->league->name ?? 'Novice',
                    'avg_wpm' => $participant->user->profile->typing_speed_avg,
                    'is_bot' => false,
                ];
            });

        $numberOfBots = max(0, 3 - $humanParticipants->count());
        $bots = [];
        
        if ($numberOfBots > 0) {
            $bots = $this->botService->generateBots(
                $numberOfBots, 
                $competition->text->difficulty_level
            );
        }

        return [
            'humans' => $humanParticipants->toArray(),
            'bots' => $bots,
            'total_participants' => $humanParticipants->count() + count($bots),
        ];
    }
}
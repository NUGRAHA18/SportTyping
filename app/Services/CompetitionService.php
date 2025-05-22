<?php
namespace App\Services;

use App\Models\Competition;
use App\Models\User;
use App\Events\CompetitionStarted;
use App\Events\CompetitionProgress;
use App\Events\CompetitionEnded;

class CompetitionService
{
    protected $botService;

    public function __construct(BotService $botService)
    {
        $this->botService = $botService;
    }
    
    public function startCompetition(Competition $competition)
    {
        // Update competition status
        $competition->status = 'active';
        $competition->save();
        
        // Broadcast competition started event
        event(new CompetitionStarted($competition));
        
        return true;
    }
    
    public function updateCompetitionProgress(Competition $competition, User $user, int $progress)
    {
        // Broadcast progress update
        event(new CompetitionProgress($competition, $user, $progress));
        
        return true;
    }
    
    public function endCompetition(Competition $competition)
    {
        // Update competition status
        $competition->status = 'completed';
        $competition->end_time = now();
        $competition->save();
        
        // Broadcast competition ended event
        event(new CompetitionEnded($competition));
        
        return true;
    }
    
    public function getCompetitionParticipants(Competition $competition)
    {
        $humanParticipants = $competition->participants()
            ->with('user.profile')
            ->where('is_bot', false)
            ->get();
            
        $numberOfBots = max(0, 3 - $humanParticipants->count());
        
        $bots = [];
        if ($numberOfBots > 0) {
            $bots = $this->botService->generateBots(
                $numberOfBots, 
                $competition->text->difficulty_level
            );
        }
        
        return [
            'human' => $humanParticipants,
            'bots' => $bots
        ];
    }
}
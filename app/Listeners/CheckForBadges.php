<?php

namespace App\Listeners;

use App\Events\UserEarnedExperience;
use App\Services\BadgeService;
use App\Services\LeagueService;

class CheckForBadges
{
    protected $badgeService;
    protected $leagueService;

    /**
     * Create the event listener.
     */
    public function __construct(BadgeService $badgeService, LeagueService $leagueService)
    {
        $this->badgeService = $badgeService;
        $this->leagueService = $leagueService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserEarnedExperience $event): void
    {
        // Check for experience badges
        $user = $event->user->fresh();
        
        // Check for league progression
        $this->leagueService->updateUserLeague($user);
    }
}
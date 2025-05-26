<?php
namespace App\Listeners;

use App\Events\LeaguePromoted;

class NotifyUserOfLeaguePromotion
{
    public function __construct()
    {
        //
    }

    public function handle(LeaguePromoted $event): void
    {
        // Code to notify user about league promotion
        // For now, we'll just log it
        \Log::info('User ' . $event->user->username . ' promoted from ' . 
            ($event->oldLeague ? $event->oldLeague->name : 'no league') . 
            ' to ' . $event->newLeague->name);
        
        // In a real app, you might send an email or notification
        // $event->user->notify(new \App\Notifications\LeaguePromotion($event->oldLeague, $event->newLeague));
    }
}
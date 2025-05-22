<?php
namespace App\Listeners;

use App\Events\BadgeEarned;

class NotifyUserOfBadgeEarned
{
    public function __construct()
    {
        //
    }

    public function handle(BadgeEarned $event): void
    {
        // Code to notify user about new badge
        // For now, we'll just log it
        \Log::info('User ' . $event->user->username . ' earned badge: ' . $event->badge->name);
        
        // In a real app, you might send an email or notification
        // $event->user->notify(new \App\Notifications\NewBadgeEarned($event->badge));
    }
}
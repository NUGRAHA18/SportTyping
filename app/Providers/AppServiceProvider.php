<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Services\WPMCalculationService;
use App\Events\UserEarnedExperience;
use App\Events\BadgeEarned;
use App\Events\LeaguePromoted;
use App\Listeners\CheckForBadges;
use App\Listeners\NotifyUserOfBadgeEarned;
use App\Listeners\NotifyUserOfLeaguePromotion;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register WPMCalculationService as singleton
        $this->app->singleton(WPMCalculationService::class, function ($app) {
            return new WPMCalculationService();
        });
    }

    public function boot(): void
    {
        // Manual event registration (optional - Event Discovery should handle this automatically)
        // Only register if you want to ensure specific event-listener relationships
        
        Event::listen(
            UserEarnedExperience::class,
            CheckForBadges::class
        );
        
        Event::listen(
            BadgeEarned::class,
            NotifyUserOfBadgeEarned::class
        );
        
        Event::listen(
            LeaguePromoted::class,
            NotifyUserOfLeaguePromotion::class
        );
    }
}
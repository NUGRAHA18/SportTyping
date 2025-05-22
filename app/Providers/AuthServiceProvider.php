<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Competition::class => \App\Policies\CompetitionPolicy::class,
        \App\Models\User::class => \App\Policies\ProfilePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
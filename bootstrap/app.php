<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Add API routes
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        $middleware->alias([
            'check.competition.access' => \App\Http\Middleware\CheckCompetitionAccess::class,
            'force.guest' => \App\Http\Middleware\ForceGuest::class,
            'api.rate.limit' => \App\Http\Middleware\ApiRateLimiting::class,
        ]);
        
        // Global middleware
        $middleware->validateCsrfTokens(except: [
            'api/*', // Exclude API routes from CSRF
        ]);
        
        // Throttle API requests
        $middleware->throttleApi('120,1'); // 120 requests per minute for API
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling is already in app/Exceptions/Handler.php
    })
    ->withSchedule(function ($schedule) {
        // Alternative way to define schedule here if needed
    })
    ->create();
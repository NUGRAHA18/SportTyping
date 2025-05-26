<?php

use App\Http\Controllers\Api\V1\CompetitionApiController;
use App\Http\Controllers\Api\V1\PracticeApiController;
use App\Http\Controllers\Api\V1\UserApiController;
use Illuminate\Support\Facades\Route;

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Public API routes
    Route::post('/guest/calculate-wmp', [PracticeApiController::class, 'calculateGuestWMP'])
        ->middleware(['throttle:60,1']);
    
    // Protected API routes
    Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {
        
        // Competition API
        Route::prefix('competitions')->group(function () {
            Route::get('/{competition}/participants', [CompetitionApiController::class, 'getParticipants']);
            Route::post('/{competition}/progress', [CompetitionApiController::class, 'updateProgress']);
            Route::post('/{competition}/real-time-stats', [CompetitionApiController::class, 'getRealTimeStats']);
        });
        
        // Practice API
        Route::prefix('practice')->group(function () {
            Route::post('/calculate-stats', [PracticeApiController::class, 'calculateRealTimeStats']);
            Route::post('/validate-typing', [PracticeApiController::class, 'validateTyping']);
        });
        
        // User API
        Route::prefix('user')->group(function () {
            Route::get('/dashboard', [UserApiController::class, 'getDashboardData']);
            Route::get('/recent-activity', [UserApiController::class, 'getRecentActivity']);
            Route::get('/statistics', [UserApiController::class, 'getStatistics']);
        });
    });
});

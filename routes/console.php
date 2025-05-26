<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\UpdateLeaderboardsJob;
use App\Services\CompetitionTimerService;

// Default inspire command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Competition status check command
Artisan::command('competition:check-status', function () {
    $timerService = app(CompetitionTimerService::class);
    
    $this->info('Checking competition statuses...');
    
    $updated = $timerService->checkAndUpdateCompetitionStatuses();
    
    $this->info("Started competitions: {$updated['started']}");
    $this->info("Ended competitions: {$updated['ended']}");
    
    return 0;
})->purpose('Check and update competition statuses');

// User league update command  
Artisan::command('users:update-leagues', function () {
    $this->info('Updating user leagues...');
    
    $leagueService = app(\App\Services\LeagueService::class);
    $users = \App\Models\User::with('profile')->get();
    
    $updated = 0;
    foreach ($users as $user) {
        if ($leagueService->updateUserLeague($user)) {
            $updated++;
        }
    }
    
    $this->info("Updated {$updated} user leagues");
    
    return 0;
})->purpose('Update user leagues based on experience');

// Test Pusher command with SSL fix
Artisan::command('test:pusher', function () {
    try {
        // Get Pusher configuration with SSL fix
        $options = config('broadcasting.connections.pusher.options');
        
        $pusher = new \Pusher\Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            $options
        );
        
        $data = [
            'message' => 'Hello from SportTyping!',
            'timestamp' => now()->toDateTimeString(),
            'test_id' => uniqid()
        ];
        
        $result = $pusher->trigger('test-channel', 'test-event', $data);
        
        $this->info('✅ Pusher test successful!');
        $this->info('📡 Channel: test-channel');
        $this->info('🎯 Event: test-event');
        $this->info('🔑 App ID: ' . config('broadcasting.connections.pusher.app_id'));
        $this->info('🌍 Cluster: ' . config('broadcasting.connections.pusher.options.cluster'));
        $this->info('📊 Result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        if ($result) {
            $this->info('🚀 Real-time broadcasting is working!');
        }
        
    } catch (\Exception $e) {
        $this->error('❌ Pusher test failed: ' . $e->getMessage());
        $this->newLine();
        $this->warn('🔧 Troubleshooting tips:');
        $this->line('1. Check your Pusher credentials in .env');
        $this->line('2. Verify your internet connection');
        $this->line('3. Check if your firewall is blocking HTTPS requests');
        $this->line('4. Try running: php artisan config:clear');
    }
})->purpose('Test Pusher broadcasting connection');

// TASK SCHEDULING - Laravel 11+ way
Schedule::job(new UpdateLeaderboardsJob())->hourly()->name('update-leaderboards');

Schedule::call(function () {
    $timerService = app(CompetitionTimerService::class);
    $timerService->checkAndUpdateCompetitionStatuses();
})->everyMinute()->name('check-competitions');

Schedule::command('users:update-leagues')->daily()->name('update-user-leagues');

// Clear expired cache entries daily
Schedule::command('cache:prune-stale-tags')->daily()->name('prune-cache');

// Backup database daily at 2 AM (if you have backup package)
Schedule::command('backup:run')->dailyAt('02:00')->name('database-backup');

// Clean up old logs weekly
Schedule::call(function () {
    $files = glob(storage_path('logs/*.log'));
    $oneWeekAgo = time() - (7 * 24 * 60 * 60);
    
    foreach ($files as $file) {
        if (filemtime($file) < $oneWeekAgo) {
            unlink($file);
        }
    }
})->weekly()->name('cleanup-logs');

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Default inspire command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

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
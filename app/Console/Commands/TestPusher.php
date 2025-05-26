<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pusher\Pusher;

class TestPusher extends Command
{
    protected $signature = 'test:pusher';
    protected $description = 'Test Pusher connection';

    public function handle()
    {
        try {
            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options')
            );
            
            $pusher->trigger('test-channel', 'test-event', [
                'message' => 'Hello from SportTyping!'
            ]);
            
            $this->info('✅ Pusher test successful!');
        } catch (\Exception $e) {
            $this->error('❌ Pusher test failed: ' . $e->getMessage());
        }
    }
}
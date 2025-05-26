<?php
namespace App\Events;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompetitionProgress implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $competition;
    public $user;
    public $progressData;

    public function __construct(Competition $competition, User $user, array $progressData)
    {
        $this->competition = $competition;
        $this->user = $user;
        $this->progressData = $progressData;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('competition.' . $this->competition->id);
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'username' => $this->user->username,
            'avatar' => $this->user->profile->avatar ?? null,
            'progress' => $this->progressData['progress'] ?? 0,
            'wpm' => $this->progressData['wpm'] ?? 0,
            'accuracy' => $this->progressData['accuracy'] ?? 0,
            'position' => $this->progressData['position'] ?? 0,
            'is_finished' => $this->progressData['progress'] >= 100,
            'timestamp' => now()->toISOString(),
        ];
    }

    public function broadcastAs()
    {
        return 'progress.updated';
    }
}
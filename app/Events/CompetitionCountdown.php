<?php
namespace App\Events;

use App\Models\Competition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompetitionCountdown implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $competition;
    public $remainingTime;

    public function __construct(Competition $competition, int $remainingTime)
    {
        $this->competition = $competition;
        $this->remainingTime = $remainingTime;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('competition.' . $this->competition->id);
    }

    public function broadcastWith()
    {
        return [
            'remaining_time' => $this->remainingTime,
            'status' => $this->competition->status,
            'timestamp' => now()->toISOString(),
        ];
    }

    public function broadcastAs()
    {
        return 'countdown.tick';
    }
}
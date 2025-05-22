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
    public $progress;

    public function __construct(Competition $competition, User $user, int $progress)
    {
        $this->competition = $competition;
        $this->user = $user;
        $this->progress = $progress;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('competition.' . $this->competition->id);
    }
}
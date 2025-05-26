<?php
namespace App\Events;

use App\Models\Competition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompetitionEnded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $competition;

    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('competition.' . $this->competition->id);
    }
}
<?php
namespace App\Events;

use App\Models\League;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaguePromoted
{
    use Dispatchable, SerializesModels;

    public $user;
    public $oldLeague;
    public $newLeague;

    public function __construct(User $user, ?League $oldLeague, League $newLeague)
    {
        $this->user = $user;
        $this->oldLeague = $oldLeague;
        $this->newLeague = $newLeague;
    }
}
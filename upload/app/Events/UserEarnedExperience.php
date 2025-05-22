<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEarnedExperience
{
    use Dispatchable, SerializesModels;

    public $user;
    public $amount;
    public $source;

    public function __construct(User $user, int $amount, string $source)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->source = $source;
    }
}
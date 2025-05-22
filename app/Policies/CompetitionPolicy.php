<?php
namespace App\Policies;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Competition $competition)
    {
        return true;
    }

    public function join(User $user, Competition $competition)
    {
        // User can't join if already joined
        if ($competition->participants()->where('user_id', $user->id)->exists()) {
            return false;
        }
        
        // Check device compatibility
        $userDevice = $user->profile->device_preference;
        
        if ($competition->device_type !== 'both' && $userDevice !== 'both' && $competition->device_type !== $userDevice) {
            return false;
        }
        
        return true;
    }

    public function compete(User $user, Competition $competition)
    {
        // Check if user is a participant
        if (!$competition->participants()->where('user_id', $user->id)->exists()) {
            return false;
        }
        
        // Check if competition is active
        return $competition->status === 'active';
    }
}
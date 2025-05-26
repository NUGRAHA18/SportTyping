<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $targetUser)
    {
        return true; // Anyone can view profiles
    }

    public function update(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id;
    }
}
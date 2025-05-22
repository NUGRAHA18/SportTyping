<?php
namespace App\Services;

use App\Models\League;
use App\Models\User;
use App\Events\LeaguePromoted;

class LeagueService
{
    public function updateUserLeague(User $user)
    {
        $profile = $user->profile;
        $currentExperience = $profile->total_experience;
        
        $appropriateLeague = League::where('min_experience', '<=', $currentExperience)
            ->where(function($query) use ($currentExperience) {
                $query->where('max_experience', '>=', $currentExperience)
                      ->orWhereNull('max_experience');
            })
            ->first();
            
        if ($appropriateLeague && $profile->current_league_id !== $appropriateLeague->id) {
            $oldLeague = $profile->league;
            
            $profile->current_league_id = $appropriateLeague->id;
            $profile->save();
            
            event(new LeaguePromoted($user, $oldLeague, $appropriateLeague));  // This needs the import above
            
            return true;
        }
        
        return false;
    }
}
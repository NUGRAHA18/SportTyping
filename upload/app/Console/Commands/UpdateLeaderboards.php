<?php
namespace App\Console\Commands;


use App\Models\Competition;
use App\Models\CompetitionResult;
use App\Models\League;
use App\Models\Leaderboard;
use App\Models\LeaderboardEntry;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateLeaderboards extends Command
{
    protected $signature = 'leaderboards:update';
    protected $description = 'Update all leaderboards with latest data';

    public function handle()
    {
        $this->info('Updating leaderboards...');
        
        // Update global leaderboard
        $this->updateGlobalLeaderboard();
        
        // Update league leaderboards
        $this->updateLeagueLeaderboards();
        
        // Update device-specific leaderboards
        $this->updateDeviceLeaderboards();
        
        $this->info('Leaderboards updated successfully!');
        
        return Command::SUCCESS;
    }
    
    private function updateGlobalLeaderboard()
    {
        $this->info('Updating global leaderboard...');
        
        // Create or get global leaderboard
        $leaderboard = Leaderboard::firstOrCreate(
            ['type' => 'global', 'device_type' => 'both'],
            ['name' => 'Global Leaderboard']
        );
        
        // Clear existing entries
        LeaderboardEntry::where('leaderboard_id', $leaderboard->id)->delete();
        
        // Get all users with their profiles sorted by total experience
        $users = User::with('profile')
            ->whereHas('profile')
            ->get()
            ->sortByDesc(function($user) {
                return $user->profile->total_experience;
            });
            
        // Create entries
        $rank = 1;
        foreach ($users as $user) {
            LeaderboardEntry::create([
                'leaderboard_id' => $leaderboard->id,
                'user_id' => $user->id,
                'rank' => $rank,
                'score' => $user->profile->total_experience,
            ]);
            
            $rank++;
        }
        
        $this->info("Added {$rank} entries to global leaderboard");
    }
    
    private function updateLeagueLeaderboards()
    {
        $this->info('Updating league leaderboards...');
        
        // Get all leagues
        $leagues = League::all();
        
        foreach ($leagues as $league) {
            // Create or get league leaderboard
            $leaderboard = Leaderboard::firstOrCreate(
                ['type' => 'league', 'league_id' => $league->id, 'device_type' => 'both'],
                ['name' => "{$league->name} League Leaderboard"]
            );
            
            // Clear existing entries
            LeaderboardEntry::where('leaderboard_id', $leaderboard->id)->delete();
            
            // Get all users in this league
            $users = User::whereHas('profile', function($query) use ($league) {
                $query->where('current_league_id', $league->id);
            })
            ->with('profile')
            ->get()
            ->sortByDesc(function($user) {
                return $user->profile->typing_speed_avg;
            });
            
            // Create entries
            $rank = 1;
            foreach ($users as $user) {
                LeaderboardEntry::create([
                    'leaderboard_id' => $leaderboard->id,
                    'user_id' => $user->id,
                    'rank' => $rank,
                    'score' => $user->profile->typing_speed_avg,
                ]);
                
                $rank++;
            }
            
            $this->info("Added {$rank} entries to {$league->name} league leaderboard");
        }
    }
    
    private function updateDeviceLeaderboards()
    {
        $this->info('Updating device leaderboards...');
        
        // Device types
        $deviceTypes = ['mobile', 'pc'];
        
        foreach ($deviceTypes as $deviceType) {
            // Create or get device leaderboard
            $leaderboard = Leaderboard::firstOrCreate(
                ['type' => 'device_type', 'device_type' => $deviceType],
                ['name' => ucfirst($deviceType) . " Leaderboard"]
            );
            
            // Clear existing entries
            LeaderboardEntry::where('leaderboard_id', $leaderboard->id)->delete();
            
            // Get competition results for this device type
            $results = CompetitionResult::whereHas('competition', function($query) use ($deviceType) {
                $query->where('device_type', $deviceType);
            })
            ->with('user.profile')
            ->get()
            ->groupBy('user_id')
            ->map(function($userResults) {
                return $userResults->avg('typing_speed');
            })
            ->sort()
            ->reverse();
            
            // Create entries
            $rank = 1;
            foreach ($results as $userId => $avgSpeed) {
                LeaderboardEntry::create([
                    'leaderboard_id' => $leaderboard->id,
                    'user_id' => $userId,
                    'rank' => $rank,
                    'score' => $avgSpeed,
                ]);
                
                $rank++;
            }
            
            $this->info("Added {$rank} entries to {$deviceType} leaderboard");
        }
    }
}

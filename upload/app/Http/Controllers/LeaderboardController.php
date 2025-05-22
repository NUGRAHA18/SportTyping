<?php
namespace App\Http\Controllers;

use App\Models\Leaderboard;
use App\Models\LeaderboardEntry;
use App\Models\League;
use App\Models\TextCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $globalLeaderboards = Leaderboard::where('type', 'global')
            ->get();
            
        $leagueLeaderboards = Leaderboard::where('type', 'league')
            ->with('league')
            ->get();
            
        $deviceLeaderboards = Leaderboard::where('type', 'device_type')
            ->get();
            
        return view('leaderboards.index', compact(
            'globalLeaderboards',
            'leagueLeaderboards',
            'deviceLeaderboards'
        ));
    }
    
    public function show(Leaderboard $leaderboard, Request $request)
    {
        $entries = LeaderboardEntry::where('leaderboard_id', $leaderboard->id)
            ->with('user.profile')
            ->orderBy('rank')
            ->paginate(20);
            
        $userEntry = LeaderboardEntry::where('leaderboard_id', $leaderboard->id)
            ->where('user_id', Auth::id())
            ->first();
            
        $leagueInfo = null;
        $deviceInfo = null;
        
        if ($leaderboard->type == 'league' && $leaderboard->league_id) {
            $leagueInfo = League::find($leaderboard->league_id);
        }
        
        if ($leaderboard->type == 'device_type') {
            $deviceInfo = $leaderboard->device_type;
        }
        
        return view('leaderboards.show', compact(
            'leaderboard',
            'entries',
            'userEntry',
            'leagueInfo',
            'deviceInfo'
        ));
    }
}
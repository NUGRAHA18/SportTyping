<?php
namespace App\Http\Controllers;

use App\Models\Leaderboard;
use App\Models\LeaderboardEntry;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    public function index()
    {
        $cacheKey = 'leaderboards.index';
        
        $leaderboards = Cache::remember($cacheKey, 600, function () { // 10 minutes cache
            return [
                'global' => Leaderboard::where('type', 'global')->get(),
                'league' => Leaderboard::where('type', 'league')
                    ->with(['league:id,name'])
                    ->get(),
                'device' => Leaderboard::where('type', 'device_type')->get(),
            ];
        });
            
        return view('leaderboards.index', [
            'globalLeaderboards' => $leaderboards['global'],
            'leagueLeaderboards' => $leaderboards['league'],
            'deviceLeaderboards' => $leaderboards['device'],
        ]);
    }
    
    public function show(Leaderboard $leaderboard, Request $request)
    {
        $page = $request->get('page', 1);
        $cacheKey = "leaderboard.{$leaderboard->id}.page.{$page}";
        
        $data = Cache::remember($cacheKey, 300, function () use ($leaderboard) { // 5 minutes cache
            $entries = LeaderboardEntry::where('leaderboard_id', $leaderboard->id)
                ->with(['user:id,username', 'user.profile:user_id,avatar,current_league_id'])
                ->orderBy('rank')
                ->paginate(20);
                
            $leagueInfo = null;
            if ($leaderboard->type == 'league' && $leaderboard->league_id) {
                $leagueInfo = League::find($leaderboard->league_id);
            }
            
            return compact('entries', 'leagueInfo');
        });
        
        $userEntry = Cache::remember("leaderboard.{$leaderboard->id}.user." . Auth::id(), 300, function () use ($leaderboard) {
            return LeaderboardEntry::where('leaderboard_id', $leaderboard->id)
                ->where('user_id', Auth::id())
                ->with(['user:id,username', 'user.profile:user_id,avatar'])
                ->first();
        });
        
        return view('leaderboards.show', array_merge($data, [
            'leaderboard' => $leaderboard,
            'userEntry' => $userEntry,
            'deviceInfo' => $leaderboard->type == 'device_type' ? $leaderboard->device_type : null,
        ]));
    }
}
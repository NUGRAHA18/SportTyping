<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all badges - fix: remove category ordering since column doesn't exist
        $allBadges = Badge::orderBy('requirement_type')
            ->orderBy('requirement_value')
            ->get();
        
        // Group badges by requirement type for better organization
        $badgesByType = $allBadges->groupBy('requirement_type');
        
        // Get user's earned badges
        $userBadges = collect();
        $userStats = [];
        $recentBadges = collect();
        $nextBadges = collect();
        
        if ($user) {
            $userBadges = $user->badges()
                ->withPivot('created_at')
                ->orderBy('pivot_created_at', 'desc')
                ->get();
            
            // Get user stats for badge progress calculation
            $userStats = [
                'experience' => $user->experiences()->sum('amount') ?? 0,
                'best_accuracy' => $user->practices()->max('accuracy') ?? 0,
                'best_speed' => $user->practices()->max('wpm') ?? 0,
                'total_competitions' => $user->competitions()->count(),
                'competitions_won' => $user->competitionResults()
                    ->whereHas('competition', function($q) {
                        $q->where('status', 'completed');
                    })
                    ->where('position', 1)
                    ->count(),
                'lessons_completed' => $user->lessonProgresses()
                    ->whereNotNull('completed_at')
                    ->count()
            ];
            
            // Get recently earned badges (last 30 days)
            $recentBadges = $userBadges->filter(function($badge) {
                return $badge->pivot->created_at >= now()->subDays(30);
            })->take(5);
            
            // Get next badges user is close to earning
            $earnedBadgeIds = $userBadges->pluck('id');
            $nextBadges = $allBadges->whereNotIn('id', $earnedBadgeIds)
                ->filter(function($badge) use ($userStats) {
                    return $this->getBadgeProgress($badge, $userStats) >= 50;
                })
                ->sortByDesc(function($badge) use ($userStats) {
                    return $this->getBadgeProgress($badge, $userStats);
                })
                ->take(3);
        }
        
        return view('badges.index', compact(
            'allBadges',
            'badgesByType',
            'userBadges', 
            'userStats',
            'recentBadges',
            'nextBadges'
        ));
    }
    
    private function getBadgeProgress($badge, $userStats)
    {
        switch ($badge->requirement_type) {
            case 'experience':
                return min(100, ($userStats['experience'] / $badge->requirement_value) * 100);
            case 'accuracy':
                return min(100, ($userStats['best_accuracy'] / $badge->requirement_value) * 100);
            case 'speed':
                return min(100, ($userStats['best_speed'] / $badge->requirement_value) * 100);
            case 'competitions':
                return min(100, ($userStats['total_competitions'] / $badge->requirement_value) * 100);
            case 'wins':
                return min(100, ($userStats['competitions_won'] / $badge->requirement_value) * 100);
            case 'lessons':
                return min(100, ($userStats['lessons_completed'] / $badge->requirement_value) * 100);
            default:
                return 0;
        }
    }
}
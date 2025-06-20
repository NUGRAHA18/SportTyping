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
        
        // Get all badges
        $allBadges = Badge::orderBy('category')
            ->orderBy('requirement_value')
            ->get();
        
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
                'best_wpm' => $user->practices()->max('wpm') ?? 0,
                'best_accuracy' => $user->practices()->max('accuracy') ?? 0,
                'total_practices' => $user->practices()->count(),
                'competitions_won' => $user->competitionResults()
                    ->whereHas('competition', function($q) {
                        $q->whereRaw('(SELECT COUNT(*) FROM competition_results cr2 WHERE cr2.competition_id = competitions.id AND cr2.wmp > competition_results.wpm) = 0');
                    })->count(),
                'consecutive_days' => $this->calculateConsecutiveDays($user)
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
            'userBadges', 
            'userStats',
            'recentBadges',
            'nextBadges'
        ));
    }
    
    private function calculateConsecutiveDays($user)
    {
        $practices = $user->practices()
            ->selectRaw('DATE(created_at) as practice_date')
            ->groupBy('practice_date')
            ->orderBy('practice_date', 'desc')
            ->pluck('practice_date')
            ->map(function($date) {
                return \Carbon\Carbon::parse($date);
            });
        
        if ($practices->isEmpty()) return 0;
        
        $consecutiveDays = 1;
        $currentDate = $practices->first();
        
        foreach ($practices->skip(1) as $practiceDate) {
            if ($currentDate->diffInDays($practiceDate) === 1) {
                $consecutiveDays++;
                $currentDate = $practiceDate;
            } else {
                break;
            }
        }
        
        return $consecutiveDays;
    }
    
    private function getBadgeProgress($badge, $userStats)
    {
        switch ($badge->requirement_type) {
            case 'wpm':
                return min(100, ($userStats['best_wpm'] / $badge->requirement_value) * 100);
            case 'accuracy':
                return min(100, ($userStats['best_accuracy'] / $badge->requirement_value) * 100);
            case 'practices_completed':
                return min(100, ($userStats['total_practices'] / $badge->requirement_value) * 100);
            case 'competitions_won':
                return min(100, ($userStats['competitions_won'] / $badge->requirement_value) * 100);
            case 'consecutive_days':
                return min(100, ($userStats['consecutive_days'] / $badge->requirement_value) * 100);
            default:
                return 0;
        }
    }
}
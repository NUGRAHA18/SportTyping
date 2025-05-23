<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cacheKey = "user.{$user->id}.dashboard";
        
        $dashboardData = Cache::remember($cacheKey, 300, function () use ($user) { // 5 minutes cache
            $user->load('profile.league', 'badges');
            
            $recentCompetitions = $user->competitionResults()
                ->with(['competition:id,title,start_time'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $recentPractices = $user->practices()
                ->with(['text:id,title,category_id', 'text.category:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $lessonProgress = $user->lessonProgress()
                ->with(['lesson:id,title,order_number'])
                ->get();
                
            $completedLessons = $lessonProgress->where('completion_status', 'completed')->count();
            $totalLessons = Cache::remember('total_lessons_count', 3600, function () {
                return \App\Models\TypingLesson::count();
            });
            
            return compact(
                'user', 
                'recentCompetitions', 
                'recentPractices', 
                'completedLessons', 
                'totalLessons'
            );
        });
        
        return view('dashboard.index', $dashboardData);
    }

    public function getRecentActivity()
    {
        $user = Auth::user();
        $cacheKey = "user.{$user->id}.recent_activity";
        
        $activity = Cache::remember($cacheKey, 180, function () use ($user) { // 3 minutes cache
            $competitions = $user->competitionResults()
                ->with(['competition:id,title'])
                ->latest()
                ->limit(3)
                ->get()
                ->map(function ($result) {
                    return [
                        'type' => 'competition',
                        'title' => $result->competition->title,
                        'wpm' => $result->typing_speed, // FIXED: was wmp
                        'accuracy' => $result->typing_accuracy,
                        'date' => $result->created_at->toISOString(),
                    ];
                });

            $practices = $user->practices()
                ->with(['text:id,title'])
                ->latest()
                ->limit(3)
                ->get()
                ->map(function ($practice) {
                    return [
                        'type' => 'practice',
                        'title' => $practice->text->title,
                        'wpm' => $practice->typing_speed, // FIXED: was wmp
                        'accuracy' => $practice->typing_accuracy,
                        'date' => $practice->created_at->toISOString(),
                    ];
                });

            return $competitions->concat($practices)
                ->sortByDesc('date')
                ->take(5)
                ->values();
        });

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }
}
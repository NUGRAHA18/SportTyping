<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserApiController extends BaseApiController
{
    public function getDashboardData(Request $request)
    {
        try {
            $user = $request->user();
            $cacheKey = "api.user.{$user->id}.dashboard";
            
            $data = Cache::remember($cacheKey, 300, function () use ($user) {
                return [
                    'profile' => [
                        'username' => $user->username,
                        'email' => $user->email,
                        'avatar' => $user->profile->avatar,
                        'league' => $user->profile->league->name ?? 'Novice',
                        'total_experience' => $user->profile->total_experience,
                        'typing_speed_avg' => $user->profile->typing_speed_avg,
                        'typing_accuracy_avg' => $user->profile->typing_accuracy_avg,
                        'total_competitions' => $user->profile->total_competitions,
                        'total_wins' => $user->profile->total_wins,
                    ],
                    'recent_badges' => $user->badges()
                        ->orderBy('pivot_created_at', 'desc')
                        ->limit(3)
                        ->get(['id', 'name', 'icon']),
                    'stats_summary' => [
                        'competitions_this_month' => $user->competitionResults()
                            ->whereMonth('created_at', now()->month)
                            ->count(),
                        'practices_this_week' => $user->practices()
                            ->whereDate('created_at', '>=', now()->subWeek())
                            ->count(),
                        'lessons_completed' => $user->lessonProgress()
                            ->where('completion_status', 'completed')
                            ->count(),
                    ]
                ];
            });

            return $this->successResponse($data, 'Dashboard data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve dashboard data', 500);
        }
    }

    public function getRecentActivity(Request $request)
    {
        try {
            $user = $request->user();
            $limit = $request->get('limit', 10);
            
            $cacheKey = "api.user.{$user->id}.recent_activity.{$limit}";
            
            $activity = Cache::remember($cacheKey, 180, function () use ($user, $limit) {
                $competitions = $user->competitionResults()
                    ->with(['competition:id,title'])
                    ->latest()
                    ->limit($limit)
                    ->get()
                    ->map(function ($result) {
                        return [
                            'id' => $result->id,
                            'type' => 'competition',
                            'title' => $result->competition->title,
                            'wmp' => $result->typing_speed,
                            'accuracy' => $result->typing_accuracy,
                            'experience' => $result->experience_earned,
                            'date' => $result->created_at->toISOString(),
                        ];
                    });

                $practices = $user->practices()
                    ->with(['text:id,title'])
                    ->latest()
                    ->limit($limit)
                    ->get()
                    ->map(function ($practice) {
                        return [
                            'id' => $practice->id,
                            'type' => 'practice',
                            'title' => $practice->text->title,
                            'wmp' => $practice->typing_speed,
                            'accuracy' => $practice->typing_accuracy,
                            'experience' => $practice->experience_earned,
                            'date' => $practice->created_at->toISOString(),
                        ];
                    });

                return $competitions->concat($practices)
                    ->sortByDesc('date')
                    ->take($limit)
                    ->values();
            });

            return $this->successResponse($activity, 'Recent activity retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve recent activity', 500);
        }
    }

    public function getStatistics(Request $request)
    {
        try {
            $user = $request->user();
            $cacheKey = "api.user.{$user->id}.statistics";
            
            $stats = Cache::remember($cacheKey, 600, function () use ($user) {
                $competitions = $user->competitionResults();
                $practices = $user->practices();
                
                return [
                    'overall' => [
                        'total_competitions' => $competitions->count(),
                        'total_practices' => $practices->count(),
                        'avg_wmp' => round(($competitions->avg('typing_speed') + $practices->avg('typing_speed')) / 2, 2),
                        'avg_accuracy' => round(($competitions->avg('typing_accuracy') + $practices->avg('typing_accuracy')) / 2, 2),
                        'total_experience' => $user->profile->total_experience,
                    ],
                    'competitions' => [
                        'total' => $competitions->count(),
                        'wins' => $user->profile->total_wins,
                        'avg_wmp' => $competitions->avg('typing_speed'),
                        'avg_accuracy' => $competitions->avg('typing_accuracy'),
                        'best_wmp' => $competitions->max('typing_speed'),
                        'best_accuracy' => $competitions->max('typing_accuracy'),
                    ],
                    'practices' => [
                        'total' => $practices->count(),
                        'avg_wmp' => $practices->avg('typing_speed'),
                        'avg_accuracy' => $practices->avg('typing_accuracy'),
                        'best_wmp' => $practices->max('typing_speed'),
                        'best_accuracy' => $practices->max('typing_accuracy'),
                    ],
                    'progress' => [
                        'current_league' => $user->profile->league->name ?? 'Novice',
                        'badges_earned' => $user->badges()->count(),
                        'lessons_completed' => $user->lessonProgress()->where('completion_status', 'completed')->count(),
                    ]
                ];
            });

            return $this->successResponse($stats, 'Statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }
}
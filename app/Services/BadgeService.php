<?php
namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use App\Events\BadgeEarned;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BadgeService
{
    public function checkAndAwardSpeedBadges(User $user, float $typingSpeed): array
    {
        return $this->checkAndAwardBadgesByType($user, 'speed', $typingSpeed);
    }
    
    public function checkAndAwardAccuracyBadges(User $user, float $accuracy): array
    {
        return $this->checkAndAwardBadgesByType($user, 'accuracy', $accuracy);
    }
    
    public function checkAndAwardCompetitionBadges(User $user): array
    {
        $totalCompetitions = $user->profile->total_competitions;
        return $this->checkAndAwardBadgesByType($user, 'competitions', $totalCompetitions);
    }
    
    public function checkAndAwardWinsBadges(User $user): array
    {
        $totalWins = $user->profile->total_wins;
        return $this->checkAndAwardBadgesByType($user, 'wins', $totalWins);
    }
    
    public function checkAndAwardLessonBadges(User $user): array
    {
        $completedLessons = $user->lessonProgress()
            ->where('completion_status', 'completed')
            ->count();
            
        return $this->checkAndAwardBadgesByType($user, 'lessons', $completedLessons);
    }

    private function checkAndAwardBadgesByType(User $user, string $type, float $value): array
    {
        try {
            $cacheKey = "badges.{$type}.eligible";
            
            // Cache eligible badges for this type
            $eligibleBadges = Cache::remember($cacheKey, 3600, function () use ($type, $value) {
                return Badge::where('requirement_type', $type)
                    ->where('requirement_value', '<=', $value)
                    ->orderBy('requirement_value', 'asc')
                    ->get();
            });

            $awardedBadges = [];
            $userBadgeIds = $user->badges->pluck('id')->toArray();

            foreach ($eligibleBadges as $badge) {
                if (!in_array($badge->id, $userBadgeIds)) {
                    if ($this->awardBadgeIfNotExists($user, $badge)) {
                        $awardedBadges[] = $badge;
                        $userBadgeIds[] = $badge->id; // Update local cache
                    }
                }
            }

            if (!empty($awardedBadges)) {
                Log::info('Badges awarded', [
                    'user_id' => $user->id,
                    'type' => $type,
                    'value' => $value,
                    'badges' => array_column($awardedBadges, 'name')
                ]);
            }

            return $awardedBadges;

        } catch (\Exception $e) {
            Log::error('Failed to check and award badges', [
                'user_id' => $user->id,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
    
    private function awardBadgeIfNotExists(User $user, Badge $badge): bool
    {
        try {
            DB::beginTransaction();

            // Double-check to prevent race conditions
            $exists = UserBadge::where('user_id', $user->id)
                ->where('badge_id', $badge->id)
                ->exists();

            if (!$exists) {
                UserBadge::create([
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                ]);
                
                event(new BadgeEarned($user, $badge));
                
                // Clear user badge cache
                Cache::forget("user.{$user->id}.badges");
                
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to award badge', [
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'error' => $e->getMessage()
            ]);
            throw UserException::badgeAwardFailed();
        }
    }

    public function getUserBadgeProgress(User $user): array
    {
        $cacheKey = "user.{$user->id}.badge_progress";
        
        return Cache::remember($cacheKey, 600, function () use ($user) {
            $userBadges = $user->badges->pluck('id')->toArray();
            $userStats = [
                'speed' => $user->profile->typing_speed_avg,
                'accuracy' => $user->profile->typing_accuracy_avg,
                'competitions' => $user->profile->total_competitions,
                'wins' => $user->profile->total_wins,
                'lessons' => $user->lessonProgress()->where('completion_status', 'completed')->count(),
            ];

            $progress = [];
            
            foreach (['speed', 'accuracy', 'competitions', 'wins', 'lessons'] as $type) {
                $nextBadge = Badge::where('requirement_type', $type)
                    ->whereNotIn('id', $userBadges)
                    ->where('requirement_value', '>', $userStats[$type])
                    ->orderBy('requirement_value', 'asc')
                    ->first();

                if ($nextBadge) {
                    $progress[$type] = [
                        'next_badge' => $nextBadge,
                        'current_value' => $userStats[$type],
                        'required_value' => $nextBadge->requirement_value,
                        'progress_percentage' => min(100, ($userStats[$type] / $nextBadge->requirement_value) * 100)
                    ];
                }
            }

            return $progress;
        });
    }
}
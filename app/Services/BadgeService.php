<?php
namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;

class BadgeService
{
    public function checkAndAwardSpeedBadges(User $user, float $typingSpeed)
    {
        $speedBadges = Badge::where('requirement_type', 'speed')
            ->where('requirement_value', '<=', $typingSpeed)
            ->orderBy('requirement_value', 'asc')
            ->get();
            
        foreach ($speedBadges as $badge) {
            $this->awardBadgeIfNotExists($user, $badge);
        }
    }
    
    public function checkAndAwardAccuracyBadges(User $user, float $accuracy)
    {
        $accuracyBadges = Badge::where('requirement_type', 'accuracy')
            ->where('requirement_value', '<=', $accuracy)
            ->orderBy('requirement_value', 'asc')
            ->get();
            
        foreach ($accuracyBadges as $badge) {
            $this->awardBadgeIfNotExists($user, $badge);
        }
    }
    
    public function checkAndAwardCompetitionBadges(User $user)
    {
        $totalCompetitions = $user->profile->total_competitions;
        
        $competitionBadges = Badge::where('requirement_type', 'competitions')
            ->where('requirement_value', '<=', $totalCompetitions)
            ->orderBy('requirement_value', 'asc')
            ->get();
            
        foreach ($competitionBadges as $badge) {
            $this->awardBadgeIfNotExists($user, $badge);
        }
    }
    
    public function checkAndAwardWinsBadges(User $user)
    {
        $totalWins = $user->profile->total_wins;
        
        $winsBadges = Badge::where('requirement_type', 'wins')
            ->where('requirement_value', '<=', $totalWins)
            ->orderBy('requirement_value', 'asc')
            ->get();
            
        foreach ($winsBadges as $badge) {
            $this->awardBadgeIfNotExists($user, $badge);
        }
    }
    
    public function checkAndAwardLessonBadges(User $user)
    {
        $completedLessons = $user->lessonProgress()
            ->where('completion_status', 'completed')
            ->count();
            
        $lessonBadges = Badge::where('requirement_type', 'lessons')
            ->where('requirement_value', '<=', $completedLessons)
            ->orderBy('requirement_value', 'asc')
            ->get();
            
        foreach ($lessonBadges as $badge) {
            $this->awardBadgeIfNotExists($user, $badge);
        }
    }
    
    private function awardBadgeIfNotExists(User $user, Badge $badge)
    {
        if (!UserBadge::where('user_id', $user->id)->where('badge_id', $badge->id)->exists()) {
            UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => $badge->id,
            ]);
            
            event(new BadgeEarned($user, $badge));
            
            return true;
        }
        
        return false;
    }
}
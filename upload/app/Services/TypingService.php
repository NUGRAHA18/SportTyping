<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserExperience;
use App\Models\UserPractice;
use App\Models\TypingText;
use App\Events\UserEarnedExperience;

class TypingService
{
    protected $badgeService;
    protected $leagueService;
    
    public function __construct(BadgeService $badgeService, LeagueService $leagueService)
    {
        $this->badgeService = $badgeService;
        $this->leagueService = $leagueService;
    }
    
    public function recordPracticeSession(User $user, TypingText $text, float $speed, float $accuracy, int $completionTime)
    {
        // Calculate experience based on speed, accuracy and text difficulty
        $difficultyMultiplier = $this->getDifficultyMultiplier($text->difficulty_level);
        $experienceEarned = (int) (($speed * ($accuracy / 100)) * $difficultyMultiplier);
        
        // Create practice record
        $practice = UserPractice::create([
            'user_id' => $user->id,
            'text_id' => $text->id,
            'typing_speed' => $speed,
            'typing_accuracy' => $accuracy,
            'completion_time' => $completionTime,
            'experience_earned' => $experienceEarned,
        ]);
        
        // Add experience
        UserExperience::create([
            'user_id' => $user->id,
            'amount' => $experienceEarned,
            'source_type' => 'practice',
            'source_id' => $practice->id,
        ]);

        $badgeService = app(BadgeService::class);
        $leagueService = app(LeagueService::class);

        // Panggil langsung tanpa event
        $badgeService->checkAndAwardSpeedBadges($user, $speed);
        $badgeService->checkAndAwardAccuracyBadges($user, $accuracy);
        $leagueService->updateUserLeague($user);
        
        // Update user profile
        $this->updateUserTypingStats($user);
        
        // Check for badges
        $this->badgeService->checkAndAwardSpeedBadges($user, $speed);
        $this->badgeService->checkAndAwardAccuracyBadges($user, $accuracy);
        
        // Check league progression
        $this->leagueService->updateUserLeague($user);
        
        return $practice;
    }
    
    public function updateUserTypingStats(User $user)
    {
        $profile = $user->profile;
        
        // Get all practice sessions and competition results
        $practices = $user->practices;
        $competitionResults = $user->competitionResults;
        
        if ($practices->count() > 0 || $competitionResults->count() > 0) {
            // Calculate average speed
            $totalSpeedValues = 0;
            $totalEntries = 0;
            
            if ($practices->count() > 0) {
                $totalSpeedValues += $practices->sum('typing_speed');
                $totalEntries += $practices->count();
            }
            
            if ($competitionResults->count() > 0) {
                $totalSpeedValues += $competitionResults->sum('typing_speed');
                $totalEntries += $competitionResults->count();
            }
            
            $profile->typing_speed_avg = $totalEntries > 0 ? $totalSpeedValues / $totalEntries : 0;
            
            // Calculate average accuracy
            $totalAccuracyValues = 0;
            $totalEntries = 0;
            
            if ($practices->count() > 0) {
                $totalAccuracyValues += $practices->sum('typing_accuracy');
                $totalEntries += $practices->count();
            }
            
            if ($competitionResults->count() > 0) {
                $totalAccuracyValues += $competitionResults->sum('typing_accuracy');
                $totalEntries += $competitionResults->count();
            }
            
            $profile->typing_accuracy_avg = $totalEntries > 0 ? $totalAccuracyValues / $totalEntries : 0;
            
            $profile->save();
        }
    }
    
    private function getDifficultyMultiplier(string $difficultyLevel)
    {
        return match($difficultyLevel) {
            'beginner' => 0.75,
            'intermediate' => 1.0,
            'advanced' => 1.25,
            'expert' => 1.5,
            default => 1.0,
        };
    }
}
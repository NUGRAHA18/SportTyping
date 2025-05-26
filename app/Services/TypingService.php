<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserExperience;
use App\Models\UserPractice;
use App\Models\TypingText;
use App\Events\UserEarnedExperience;
use App\Exceptions\TypingException;
use App\Exceptions\UserException;
use App\Jobs\ProcessCompetitionResultJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TypingService
{
    protected $badgeService;
    protected $leagueService;
    protected $wpmService; 

    public function __construct(
        BadgeService $badgeService, 
        LeagueService $leagueService,
        WPMCalculationService $wpmService 
    ) {
        $this->badgeService = $badgeService;
        $this->leagueService = $leagueService;
        $this->wpmService = $wpmService;
    }
    
    public function recordPracticeSession(User $user, TypingText $text, float $speed, float $accuracy, int $completionTime): UserPractice
    {
        try {
            DB::beginTransaction();

            // Validate inputs
            if ($speed < 0 || $accuracy < 0 || $accuracy > 100 || $completionTime <= 0) {
                throw TypingException::invalidTimeData();
            }

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

            // Fire event for experience earned
            event(new UserEarnedExperience($user, $experienceEarned, 'practice'));

            // Update user profile asynchronously
            dispatch(function () use ($user, $speed, $accuracy) {
                $this->updateUserTypingStats($user);
                $this->badgeService->checkAndAwardSpeedBadges($user, $speed);
                $this->badgeService->checkAndAwardAccuracyBadges($user, $accuracy);
                $this->leagueService->updateUserLeague($user);
            })->afterCommit();

            // Clear user caches
            $this->clearUserCaches($user);

            DB::commit();

            Log::info('Practice session recorded successfully', [
                'user_id' => $user->id,
                'text_id' => $text->id,
                'wpm' => $speed,
                'accuracy' => $accuracy,
                'experience' => $experienceEarned
            ]);

            return $practice;

        } catch (TypingException $e) {
            DB::rollBack();
            Log::warning('Practice session recording failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Unexpected error recording practice session', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new TypingException('Failed to record practice session');
        }
    }
    
    public function updateUserTypingStats(User $user): void
    {
        try {
            $profile = $user->profile;
            
            // Use efficient queries to calculate averages
            $practiceStats = DB::table('user_practices')
                ->where('user_id', $user->id)
                ->selectRaw('AVG(typing_speed) as avg_speed, AVG(typing_accuracy) as avg_accuracy, COUNT(*) as total')
                ->first();

            $competitionStats = DB::table('competition_results')
                ->where('user_id', $user->id)
                ->selectRaw('AVG(typing_speed) as avg_speed, AVG(typing_accuracy) as avg_accuracy, COUNT(*) as total')
                ->first();

            $totalEntries = ($practiceStats->total ?? 0) + ($competitionStats->total ?? 0);
            
            if ($totalEntries > 0) {
                $totalSpeedSum = (($practiceStats->avg_speed ?? 0) * ($practiceStats->total ?? 0)) + 
                               (($competitionStats->avg_speed ?? 0) * ($competitionStats->total ?? 0));
                               
                $totalAccuracySum = (($practiceStats->avg_accuracy ?? 0) * ($practiceStats->total ?? 0)) + 
                                  (($competitionStats->avg_accuracy ?? 0) * ($competitionStats->total ?? 0));

                $profile->typing_speed_avg = $totalSpeedSum / $totalEntries;
                $profile->typing_accuracy_avg = $totalAccuracySum / $totalEntries;
                $profile->save();

                // Clear user stats cache
                Cache::forget("user.{$user->id}.statistics");
            }

        } catch (\Exception $e) {
            Log::error('Failed to update user typing stats', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw UserException::experienceUpdateFailed();
        }
    }
    
    private function getDifficultyMultiplier(string $difficultyLevel): float
    {
        return match($difficultyLevel) {
            'beginner' => 0.75,
            'intermediate' => 1.0,
            'advanced' => 1.25,
            'expert' => 1.5,
            default => 1.0,
        };
    }

    private function clearUserCaches(User $user): void
    {
        $cacheKeys = [
            "user.{$user->id}.dashboard",
            "user.{$user->id}.profile",
            "user.{$user->id}.statistics",
            "api.user.{$user->id}.dashboard",
            "api.user.{$user->id}.recent_activity",
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
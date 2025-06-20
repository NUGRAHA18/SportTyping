<?php
namespace App\Http\Controllers;

use App\Models\TypingLesson;
use App\Models\LessonProgress;
use App\Models\UserExperience;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    protected $badgeService;
    
    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }
    
    public function index()
    {
        $user = Auth::user();
        
        $lessons = TypingLesson::orderBy('order_number')
            ->with(['progresses' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->get();
            
        $completedCount = $user->lessonProgress()
            ->where('completion_status', 'completed')
            ->count();
            
        $totalCount = TypingLesson::count();
        $completionPercentage = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;
        
        return view('lessons.index', compact('lessons', 'completedCount', 'totalCount', 'completionPercentage'));
    }
    
    public function show(TypingLesson $lesson)
    {
        $user = Auth::user();
        
        $progress = LessonProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completion_status' => 'not_started']
        );
        
        // Get next lesson
        $nextLesson = TypingLesson::where('order_number', '>', $lesson->order_number)
            ->orderBy('order_number')
            ->first();
            
        return view('lessons.show', compact('lesson', 'progress', 'nextLesson'));
    }
    
    public function updateProgress(TypingLesson $lesson, Request $request)
    {
        $validated = $request->validate([
            'typing_speed' => 'required|numeric|min:1',
            'typing_accuracy' => 'required|numeric|min:1|max:100',
            'completion_status' => 'required|in:in_progress,completed',
        ]);
        
        $user = Auth::user();
        $progress = LessonProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completion_status' => 'not_started']
        );
        
        // Update progress
        $progress->highest_speed = max($progress->highest_speed, $validated['typing_speed']);
        $progress->highest_accuracy = max($progress->highest_accuracy, $validated['typing_accuracy']);
        $progress->completion_status = $validated['completion_status'];
        
        // If completing for the first time, award experience
        $experienceEarned = 0;
        if ($validated['completion_status'] == 'completed' && $progress->experience_earned == 0) {
            $experienceEarned = $lesson->experience_reward;
            $progress->experience_earned = $experienceEarned;
            $progress->completed_at = now();
            
            // Add experience
            UserExperience::create([
                'user_id' => $user->id,
                'amount' => $experienceEarned,
                'source_type' => 'lesson',
                'source_id' => $progress->id,
            ]);
            
            // Check for lesson badges
            $this->badgeService->checkAndAwardLessonBadges($user);
        }
        
        $progress->save();
        
        return redirect()->back()->with('success', 'Your progress has been updated!' . 
            ($experienceEarned ? " You earned {$experienceEarned} experience points!" : ''));
    }
}
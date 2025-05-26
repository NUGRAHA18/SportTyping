<?php

namespace App\Http\Controllers;

use App\Models\TypingText;
use App\Models\TypingLesson;
use App\Models\Competition;
use App\Models\TextCategory;
use App\Services\BotService;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    protected $botService;
    
    public function __construct(BotService $botService = null)
    {
        $this->botService = $botService ?? new BotService();
    }
    
    public function practice()
    {
        $categories = TextCategory::withCount('texts')->get();
        $texts = TypingText::with('category')->paginate(15);
        
        return view('guest.practice', compact('texts', 'categories'));
    }
    
    public function showPractice(TypingText $text)
    {
        return view('guest.practice_show', compact('text'));
    }
    
    public function lessons()
    {
        $lessons = TypingLesson::orderBy('order_number')->get();
        
        return view('guest.lessons', compact('lessons'));
    }
    
    public function showLesson(TypingLesson $lesson)
    {
        // Get next lesson
        $nextLesson = TypingLesson::where('order_number', '>', $lesson->order_number)
            ->orderBy('order_number')
            ->first();
            
        return view('guest.lesson_show', compact('lesson', 'nextLesson'));
    }
    
    public function competitions()
    {
        $upcomingCompetitions = Competition::where('status', 'upcoming')
            ->orderBy('start_time', 'asc')
            ->get();
            
        $activeCompetitions = Competition::where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->get();
            
        return view('guest.competitions', compact('upcomingCompetitions', 'activeCompetitions'));
    }
    
    public function showCompetition(Competition $competition)
    {
        // Generate 3 bots for guest mode competition
        $bots = $this->botService->generateBots(3, $competition->text->difficulty_level);
        
        return view('guest.competition_show', compact('competition', 'bots'));
    }
}
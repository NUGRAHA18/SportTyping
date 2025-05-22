<?php
namespace App\Http\Controllers;

use App\Models\TypingText;
use App\Models\TextCategory;
use App\Services\TypingService;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticeController extends Controller
{
    protected $typingService;
    protected $badgeService;
    
    public function __construct(TypingService $typingService, BadgeService $badgeService)
    {
        $this->typingService = $typingService;
        $this->badgeService = $badgeService;
    }
    
    public function index(Request $request)
    {
        $categories = TextCategory::withCount('texts')->get();
        
        $difficulty = $request->query('difficulty');
        $categoryId = $request->query('category');
        
        $query = TypingText::query();
        
        if ($difficulty) {
            $query->where('difficulty_level', $difficulty);
        }
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $texts = $query->with('category')->paginate(15);
        
        return view('practice.index', compact('texts', 'categories', 'difficulty', 'categoryId'));
    }
    
    public function show(TypingText $text)
    {
        return view('practice.show', compact('text'));
    }
    
    public function submitResult(TypingText $text, Request $request)
    {
        $validated = $request->validate([
            'typing_speed' => 'required|numeric|min:1',
            'typing_accuracy' => 'required|numeric|min:1|max:100',
            'completion_time' => 'required|integer|min:1',
        ]);
        
        $user = Auth::user();
        
        $practice = $this->typingService->recordPracticeSession(
            $user,
            $text,
            $validated['typing_speed'],
            $validated['typing_accuracy'],
            $validated['completion_time']
        );
        
        // Check for speed and accuracy badges
        $this->badgeService->checkAndAwardSpeedBadges($user, $validated['typing_speed']);
        $this->badgeService->checkAndAwardAccuracyBadges($user, $validated['typing_accuracy']);
        
        return redirect()->route('practice.result', $practice)
            ->with('success', 'Your practice result has been recorded!');
    }
    
    public function result($practiceId)
    {
        $practice = Auth::user()->practices()->with('text')->findOrFail($practiceId);
        
        // Get user's best result for this text
        $bestPractice = Auth::user()->practices()
            ->where('text_id', $practice->text_id)
            ->orderBy('typing_speed', 'desc')
            ->first();
            
        // Get global average for this text
        $globalAverage = TypingText::find($practice->text_id)
            ->practices()
            ->avg('typing_speed');
            
        return view('practice.result', compact('practice', 'bestPractice', 'globalAverage'));
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\TypingText;
use App\Models\TextCategory;
use App\Services\TypingService;
use App\Services\BadgeService;
use App\Services\WPMCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticeController extends Controller
{
    protected $typingService;
    protected $badgeService;
    protected $wpmService;
    
    public function __construct(
        TypingService $typingService, 
        BadgeService $badgeService,
        WPMCalculationService $wpmService
    ) {
        $this->typingService = $typingService;
        $this->badgeService = $badgeService;
        $this->wpmService = $wpmService;
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
            'typed_text' => 'required|string',
            'completion_time' => 'required|integer|min:1',
        ]);
        
        $user = Auth::user();
        $originalText = $text->content;
        $typedText = $validated['typed_text'];
        $completionTime = $validated['completion_time'];
        
        // Use WPMCalculationService for accurate calculation
        $stats = $this->wpmService->calculateTypingStats(
            $originalText,
            $typedText,
            $completionTime
        );
        
        $practice = $this->typingService->recordPracticeSession(
            $user,
            $text,
            $stats['wpm'],
            $stats['accuracy'],
            $completionTime
        );
        
        // Check for speed and accuracy badges
        $this->badgeService->checkAndAwardSpeedBadges($user, $stats['wpm']);
        $this->badgeService->checkAndAwardAccuracyBadges($user, $stats['accuracy']);
        
        return redirect()->route('practice.result', $practice)
            ->with('success', 'Your practice result has been recorded!')
            ->with('stats', $stats);
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

    public function calculateRealTimeStats(Request $request)
    {
        $validated = $request->validate([
            'original_text' => 'required|string',
            'typed_text' => 'required|string',
            'elapsed_seconds' => 'required|integer|min:0'
        ]);
        
        $stats = $this->wpmService->calculateRealTimeWPM(
            $validated['original_text'],
            $validated['typed_text'],
            $validated['elapsed_seconds']
        );
        
        return response()->json($stats);
    }
}
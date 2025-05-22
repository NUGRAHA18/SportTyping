<?php
namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionParticipant;
use App\Models\CompetitionResult;
use App\Models\Device;
use App\Models\UserExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BotService;

class CompetitionController extends Controller
{
    protected $botService;

    public function __construct(BotService $botService)
    {
        $this->botService = $botService;
    }
    public function index()
    {
        $upcomingCompetitions = Competition::where('status', 'upcoming')
            ->orderBy('start_time', 'asc')
            ->get();
            
        $activeCompetitions = Competition::where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->get();
            
        return view('competitions.index', compact('upcomingCompetitions', 'activeCompetitions'));
    }

    public function show(Competition $competition)
    {
        $competition->load('text', 'participants.user');
        $userParticipating = $competition->participants()->where('user_id', Auth::id())->exists();
        
        return view('competitions.show', compact('competition', 'userParticipating'));
    }

    public function join(Competition $competition, Request $request)
    {
        // Check if user already joined
        if ($competition->participants()->where('user_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'You have already joined this competition.');
        }
        
        // Check device compatibility
        $deviceType = $request->header('User-Agent') ? 
            (preg_match('/(android|iphone|ipad|mobile)/i', $request->header('User-Agent')) ? 'mobile' : 'pc') : 
            'pc';
            
        if ($competition->device_type !== 'both' && $competition->device_type !== $deviceType) {
            return redirect()->back()->with('error', "This competition is only for {$competition->device_type} users.");
        }
        
        // Create or get device
        $device = Device::firstOrCreate(
            ['user_id' => Auth::id(), 'type' => $deviceType],
            ['name' => $request->header('User-Agent') ?? 'Unknown Device']
        );
        
        // Join competition
        CompetitionParticipant::create([
            'competition_id' => $competition->id,
            'user_id' => Auth::id(),
            'device_id' => $device->id,
        ]);
        
        return redirect()->back()->with('success', 'You have successfully joined the competition.');
    }

    public function compete(Competition $competition)
    {
        // Check if user is a participant
        if (!$competition->participants()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('competitions.show', $competition)->with('error', 'You must join the competition first.');
        }
        
        // Check if competition is active
        if ($competition->status !== 'active') {
            return redirect()->route('competitions.show', $competition)
                ->with('error', 'This competition is not currently active.');
        }
        
        $competition->load('text');
        $realParticipants = $competition->participants->count();
        $botsNeeded = max(0, 3 - $realParticipants);
        
        $bots = [];
        if ($botsNeeded > 0) {
            $bots = $this->botService->generateBots(
                $botsNeeded, 
                $competition->text->difficulty_level
            );
        }
        
        return view('competitions.compete', compact('competition', 'bots'));
    }

    public function submitResult(Competition $competition, Request $request)
    {
        $validated = $request->validate([
            'typing_speed' => 'required|numeric|min:1',
            'typing_accuracy' => 'required|numeric|min:1|max:100',
            'completion_time' => 'required|integer|min:1',
        ]);
        
        // Calculate experience earned based on speed and accuracy
        $experienceEarned = (int) (($validated['typing_speed'] * ($validated['typing_accuracy'] / 100)) * 0.5);
        
        // Create competition result
        $result = CompetitionResult::create([
            'competition_id' => $competition->id,
            'user_id' => Auth::id(),
            'typing_speed' => $validated['typing_speed'],
            'typing_accuracy' => $validated['typing_accuracy'],
            'completion_time' => $validated['completion_time'],
            'experience_earned' => $experienceEarned,
        ]);
        
        // Add experience to user
        UserExperience::create([
            'user_id' => Auth::id(),
            'amount' => $experienceEarned,
            'source_type' => 'competition',
            'source_id' => $result->id,
        ]);
        
        // Update user profile statistics
        $user = Auth::user();
        $profile = $user->profile;
        
        // Update avg speed and accuracy
        $allResults = $user->competitionResults;
        $profile->typing_speed_avg = $allResults->avg('typing_speed');
        $profile->typing_accuracy_avg = $allResults->avg('typing_accuracy');
        $profile->total_competitions += 1;
        $profile->save();
        
        return redirect()->route('competitions.result', ['competition' => $competition, 'result' => $result])
            ->with('success', 'Your result has been submitted successfully!');
    }

    public function result(Competition $competition, CompetitionResult $result)
    {
        if ($result->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $position = CompetitionResult::where('competition_id', $competition->id)
            ->where('typing_speed', '>', $result->typing_speed)
            ->count() + 1;
            
        $result->position = $position;
        $result->save();
        
        return view('competitions.result', compact('competition', 'result', 'position'));
    }
}
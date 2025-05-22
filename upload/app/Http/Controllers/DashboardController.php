<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile.league', 'badges');
        
        $recentCompetitions = $user->competitionResults()
            ->with('competition')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentPractices = $user->practices()
            ->with('text')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $lessonProgress = $user->lessonProgress()
            ->with('lesson')
            ->get();
            
        $completedLessons = $lessonProgress->where('completion_status', 'completed')->count();
        $totalLessons = \App\Models\TypingLesson::count();
        
        return view('dashboard.index', compact(
            'user', 
            'recentCompetitions', 
            'recentPractices', 
            'completedLessons', 
            'totalLessons'
        ));
    }
}
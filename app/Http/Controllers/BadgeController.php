<?php
namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all badges grouped by type
        $allBadges = Badge::all()->groupBy('requirement_type');
        
        // Get user's earned badges
        $earnedBadges = $user->badges->pluck('id')->toArray();
        
        return view('badges.index', compact('allBadges', 'earnedBadges'));
    }
}
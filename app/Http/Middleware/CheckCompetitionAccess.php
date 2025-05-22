<?php
namespace App\Http\Middleware;

use App\Models\Competition;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCompetitionAccess
{
    public function handle(Request $request, Closure $next)
    {
        $competitionId = $request->route('competition');
        
        if (!is_object($competitionId)) {
            $competition = Competition::findOrFail($competitionId);
        } else {
            $competition = $competitionId;
        }
        
        // Check if the competition is for specific device type
        if ($competition->device_type !== 'both') {
            $deviceType = $this->detectDeviceType($request);
            
            if ($competition->device_type !== $deviceType) {
                return redirect()->route('competitions.index')
                    ->with('error', "This competition is only for {$competition->device_type} users.");
            }
        }
        
        return $next($request);
    }
    
    private function detectDeviceType(Request $request)
    {
        return preg_match('/(android|iphone|ipad|mobile)/i', $request->header('User-Agent')) 
            ? 'mobile' 
            : 'pc';
    }
}
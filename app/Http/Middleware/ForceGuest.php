<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceGuest
{
    public function handle(Request $request, Closure $next)
    {
        // If user is authenticated, logout them for guest experience
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Optional: Show message about switching to guest mode
            session()->flash('info', 'Switched to guest mode. Your progress won\'t be saved.');
        }
        
        return $next($request);
    }
}
<?php
namespace App\Http\Controllers;

use App\Services\TypingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    protected $typingService;
    
    public function __construct(TypingService $typingService)
    {
        $this->typingService = $typingService;
    }
    
    public function show()
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
            
        return view('profile.show', compact('user', 'recentCompetitions', 'recentPractices'));
    }
    
    public function edit()
    {
        $user = Auth::user()->load('profile');
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'device_preference' => 'required|in:mobile,pc,both',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);
        
        // Update user information
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        
        // Update password if provided
        if (isset($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }
        
        $user->save();
        
        // Update profile
        $profile = $user->profile;
        $profile->bio = $validated['bio'] ?? $profile->bio;
        $profile->device_preference = $validated['device_preference'];
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $avatarPath;
        }
        
        $profile->save();
        
        return redirect()->route('profile.show')
            ->with('success', 'Your profile has been updated successfully!');
    }
}
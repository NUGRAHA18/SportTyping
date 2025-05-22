<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{   
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Rate limiting 
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
            ])->withInput($request->except('password'));
        }
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        
        RateLimiter::hit($throttleKey);
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            // 'g-recaptcha-response' => 'required|captcha',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha verification failed. Please try again.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create user profile
        UserProfile::create([
            'user_id' => $user->id,
            'current_league_id' => 1, // Default to Novice league
        ]);
        
        // Register event for email verification
        event(new Registered($user));

        Auth::login($user);
        return redirect('/email/verify')->with('verification-link-sent', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
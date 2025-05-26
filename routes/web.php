<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Competitions
    Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
    Route::get('/competitions/{competition}', [CompetitionController::class, 'show'])->name('competitions.show');
    Route::post('/competitions/{competition}/join', [CompetitionController::class, 'join'])->name('competitions.join');
    Route::post('/api/competitions/{competition}/real-time-stats', [CompetitionController::class, 'getRealTimeStats'])
        ->name('api.competitions.real-time-stats');
    
    // Competition routes with competition access check
    Route::middleware(['check.competition.access'])->group(function () {
        Route::get('/competitions/{competition}/compete', [CompetitionController::class, 'compete'])->name('competitions.compete');
        Route::post('/competitions/{competition}/result', [CompetitionController::class, 'submitResult'])->name('competitions.submit-result');
        Route::get('/competitions/{competition}/result/{result}', [CompetitionController::class, 'result'])->name('competitions.result');
    });

    // Email verification routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('status', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
        
    // Practice
    Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
    Route::get('/practice/{text}', [PracticeController::class, 'show'])->name('practice.show');
    Route::post('/practice/{text}/result', [PracticeController::class, 'submitResult'])->name('practice.submit-result');
    Route::post('/api/practice/calculate-stats', [PracticeController::class, 'calculateRealTimeStats'])
        ->name('api.practice.calculate-stats');
    
    // Lessons
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/lessons/{lesson}/progress', [LessonController::class, 'updateProgress'])->name('lessons.progress');
    
    // Badges
    Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');
    
    // Leaderboards
    Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards.index');
    Route::get('/leaderboards/{leaderboard}', [LeaderboardController::class, 'show'])->name('leaderboards.show');
});

Route::prefix('api/guest')->group(function () {
    // Guest real-time calculation
    Route::post('/calculate-wmp', function(Request $request) {
        $validated = $request->validate([
            'original_text' => 'required|string',
            'typed_text' => 'required|string',
            'elapsed_seconds' => 'required|integer|min:0'
        ]);
        
        $wmpService = app(App\Services\WPMCalculationService::class);
        $stats = $wmpService->calculateRealTimeWPM(
            $validated['original_text'],
            $validated['typed_text'],
            $validated['elapsed_seconds']
        );
        
        return response()->json($stats);
    })->name('api.guest.calculate-wmp');
});

Route::prefix('api')->group(function () {
    require base_path('routes/api.php');
});

Route::prefix('guest')->middleware('force.guest')->group(function () {
    Route::get('/practice', [GuestController::class, 'practice'])->name('guest.practice');
    Route::get('/practice/{text}', [GuestController::class, 'showPractice'])->name('guest.practice.show');
    Route::get('/lessons', [GuestController::class, 'lessons'])->name('guest.lessons');
    Route::get('/lessons/{lesson}', [GuestController::class, 'showLesson'])->name('guest.lessons.show');
    Route::get('/competitions', [GuestController::class, 'competitions'])->name('guest.competitions');
    Route::get('/compete/{competition}', [GuestController::class, 'showCompetition'])->name('guest.competition.show');
});

// Keyboard guide API untuk Agung
Route::get('/api/keyboard-guide/{key?}', function($key = null) {
    $keyboardGuides = [
        'home_position' => [
            'image' => '/images/keyboard/home_position.png',
            'description' => 'ASDF JKL; - Home row position',
            'left_hand' => ['A', 'S', 'D', 'F'],
            'right_hand' => ['J', 'K', 'L', ';']
        ],
        'a' => [
            'finger' => 'left pinky',
            'hand' => 'left',
            'image' => '/images/keyboard/key_a.png',
            'description' => 'Press A with your left pinky finger'
        ]
        // Sirly akan menambahkan lengkap untuk semua keys
    ];
    
    if ($key) {
        return response()->json($keyboardGuides[$key] ?? null);
    }
    
    return response()->json(array_keys($keyboardGuides));
})->name('api.keyboard-guide');
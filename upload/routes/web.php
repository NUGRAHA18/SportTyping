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
    Route::get('/competitions/{competition}/compete', [CompetitionController::class, 'compete'])->name('competitions.compete');
    Route::post('/competitions/{competition}/result', [CompetitionController::class, 'submitResult'])->name('competitions.submit-result');
    Route::get('/competitions/{competition}/result/{result}', [CompetitionController::class, 'result'])->name('competitions.result');
    
    // Practice
    Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
    Route::get('/practice/{text}', [PracticeController::class, 'show'])->name('practice.show');
    Route::post('/practice/{text}/result', [PracticeController::class, 'submitResult'])->name('practice.submit-result');
    
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

Route::prefix('guest')->group(function () {
    Route::get('/practice', [GuestController::class, 'practice'])->name('guest.practice');
    Route::get('/practice/{text}', [GuestController::class, 'showPractice'])->name('guest.practice.show');
    Route::get('/lessons', [GuestController::class, 'lessons'])->name('guest.lessons');
    Route::get('/lessons/{lesson}', [GuestController::class, 'showLesson'])->name('guest.lessons.show');
    Route::get('/competitions', [GuestController::class, 'competitions'])->name('guest.competitions');
    Route::get('/compete/{competition}', [GuestController::class, 'showCompetition'])->name('guest.competition.show');
});
<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withTimestamps()
            ->withPivot('earned_at');
    }

    public function competitionResults()
    {
        return $this->hasMany(CompetitionResult::class);
    }

    public function competitionParticipations()
    {
        return $this->hasMany(CompetitionParticipant::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function practices()
    {
        return $this->hasMany(UserPractice::class);
    }

    public function experience()
    {
        return $this->hasMany(UserExperience::class);
    }

    public function leaderboardEntries()
    {
        return $this->hasMany(LeaderboardEntry::class);
    }
}
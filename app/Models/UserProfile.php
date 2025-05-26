<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'bio',
        'typing_speed_avg',
        'typing_accuracy_avg',
        'total_competitions',
        'total_wins',
        'current_league_id',
        'total_experience',
        'device_preference',
    ];

    protected $casts = [
        'typing_speed_avg' => 'decimal:2',
        'typing_accuracy_avg' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'current_league_id');
    }
}
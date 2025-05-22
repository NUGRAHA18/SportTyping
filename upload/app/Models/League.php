<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'min_experience',
        'max_experience',
        'icon',
    ];

    public function users()
    {
        return $this->hasMany(UserProfile::class, 'current_league_id');
    }

    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class);
    }
}
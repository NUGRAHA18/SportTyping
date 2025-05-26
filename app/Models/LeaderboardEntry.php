<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'leaderboard_id',
        'user_id',
        'rank',
        'score',
    ];

    protected $casts = [
        'rank' => 'integer',
        'score' => 'decimal:2',
    ];

    public function leaderboard()
    {
        return $this->belongsTo(Leaderboard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
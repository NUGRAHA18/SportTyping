<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'device_type',
        'category_id',
        'league_id',
    ];

    public function entries()
    {
        return $this->hasMany(LeaderboardEntry::class);
    }

    public function category()
    {
        return $this->belongsTo(TextCategory::class, 'category_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }
}
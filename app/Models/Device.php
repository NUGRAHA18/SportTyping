<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competitionParticipants()
    {
        return $this->hasMany(CompetitionParticipant::class);
    }

    public function userPractices()
    {
        return $this->hasMany(UserPractice::class);
    }
}
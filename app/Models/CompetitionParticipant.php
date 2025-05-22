<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'user_id',
        'device_id',
        'is_bot',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'is_bot' => 'boolean',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
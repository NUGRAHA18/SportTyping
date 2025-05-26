<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'user_id',
        'typing_speed',
        'typing_accuracy',
        'completion_time',
        'position',
        'experience_earned',
    ];

    protected $casts = [
        'typing_speed' => 'decimal:2',
        'typing_accuracy' => 'decimal:2',
        'experience_earned' => 'integer',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
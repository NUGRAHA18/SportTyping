<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPractice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text_id',
        'typing_speed',
        'typing_accuracy',
        'completion_time',
        'experience_earned',
        'device_id',
    ];

    protected $casts = [
        'typing_speed' => 'decimal:2',
        'typing_accuracy' => 'decimal:2',
        'completion_time' => 'integer',
        'experience_earned' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function text()
    {
        return $this->belongsTo(TypingText::class, 'text_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
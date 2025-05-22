<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completion_status',
        'highest_speed',
        'highest_accuracy',
        'experience_earned',
        'completed_at',
    ];

    protected $casts = [
        'highest_speed' => 'decimal:2',
        'highest_accuracy' => 'decimal:2',
        'experience_earned' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(TypingLesson::class, 'lesson_id');
    }
}
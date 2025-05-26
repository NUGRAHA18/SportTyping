<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypingLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'difficulty_level',
        'order_number',
        'content',
        'estimated_completion_time',
        'experience_reward',
    ];

    protected $casts = [
        'order_number' => 'integer',
        'estimated_completion_time' => 'integer',
        'experience_reward' => 'integer',
    ];

    public function progress()
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }
}
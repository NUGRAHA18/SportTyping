<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypingLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'difficulty_level',
        'order_number',
        'estimated_completion_time',
        'experience_reward',
        'instructions',
        'tips',
        'show_keyboard'
    ];

    protected $casts = [
        'tips' => 'array',
        'show_keyboard' => 'boolean',
        'estimated_completion_time' => 'integer',
        'experience_reward' => 'integer',
        'order_number' => 'integer'
    ];

    /**
     * Get lesson progress for all users
     */
    public function progresses(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id'); 
    }

    /**
     * Check if lesson is completed by specific user
     */
    public function isCompletedBy($user)
    {
        if (!$user) return false;
        
        return $this->progresses()
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->exists();
    }

    /**
     * Get user's progress for this lesson
     */
    public function getProgressFor($user)
    {
        if (!$user) return null;
        
        return $this->progresses()
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * Get completion percentage for user
     */
    public function getCompletionPercentageFor($user)
    {
        $progress = $this->getProgressFor($user);
        return $progress ? $progress->completion_percentage : 0;
    }

    /**
     * Get next lesson in sequence
     */
    public function getNextLesson()
    {
        return static::where('order_number', '>', $this->order_number)
            ->orderBy('order_number', 'asc')
            ->first();
    }

    /**
     * Get previous lesson in sequence
     */
    public function getPreviousLesson()
    {
        return static::where('order_number', '<', $this->order_number)
            ->orderBy('order_number', 'desc')
            ->first();
    }

    /**
     * Scope for difficulty level
     */
    public function scopeDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    /**
     * Scope for ordered lessons
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number', 'asc');
    }
}
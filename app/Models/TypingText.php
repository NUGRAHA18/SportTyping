<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypingText extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'word_count',
        'category_id',
        'difficulty_level',
    ];

    public function category()
    {
        return $this->belongsTo(TextCategory::class, 'category_id');
    }

    public function competitions()
    {
        return $this->hasMany(Competition::class, 'text_id');
    }

    public function practices()
    {
        return $this->hasMany(UserPractice::class, 'text_id');
    }
}
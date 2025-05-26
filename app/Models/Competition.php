<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'status',
        'device_type',
        'text_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function text()
    {
        return $this->belongsTo(TypingText::class, 'text_id');
    }

    public function participants()
    {
        return $this->hasMany(CompetitionParticipant::class);
    }

    public function results()
    {
        return $this->hasMany(CompetitionResult::class);
    }
}
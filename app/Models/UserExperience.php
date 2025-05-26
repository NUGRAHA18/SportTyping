<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning source model (polymorphic).
     */
    public function source()
    {
        return $this->morphTo();
    }
}
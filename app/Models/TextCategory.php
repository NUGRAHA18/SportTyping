<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function texts()
    {
        return $this->hasMany(TypingText::class, 'category_id');
    }
}
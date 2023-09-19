<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'answer_id',
        'content',
    ];

    // $addition->user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // $addition->answer
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}

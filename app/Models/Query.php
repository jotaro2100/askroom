<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Query extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    // $query->user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // $query->answers
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}

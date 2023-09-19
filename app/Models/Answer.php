<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query_id',
        'content',
    ];

    // $answer->user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // $answer->query
    public function rootQuery()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }

    // $answer->additions
    public function additions()
    {
        return $this->hasMany(Addition::class);
    }
}

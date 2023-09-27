<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // $user->queries
    public function queries()
    {
        return $this->hasMany(Query::class);
    }

    // $user->answers
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // $user->additions
    public function additions()
    {
        return $this->hasMany(Addition::class);
    }

    public static function guestLogin()
    {
        $guest_user = User::firstOrNew(['email' => 'guest@askroom.com']);
        $guest_user->name = 'ゲスト';
        $guest_user->password = Str::random(16);
        $guest_user->save();
        Auth::login($guest_user);
    }
}

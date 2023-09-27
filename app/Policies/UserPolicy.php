<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        $guest_user = User::where('email', 'guest@askroom.com')->first();
        return $user->email !== Str::lower($guest_user->email);
    }
}

<?php

namespace App\Policies;

use App\Models\Addition;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdditionPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Addition $addition): bool
    {
        return $user->id === $addition->user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Addition $addition): bool
    {
        return $user->id === $addition->user->id;
    }
}

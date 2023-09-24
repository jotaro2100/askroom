<?php

namespace App\Policies;

use App\Models\Query;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QueryPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Query $query): bool
    {
        return $user->id === $query->user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Query $query): bool
    {
        return $user->id === $query->user->id;
    }
}

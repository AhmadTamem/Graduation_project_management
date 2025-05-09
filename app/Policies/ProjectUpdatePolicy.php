<?php

namespace App\Policies;

use App\Models\User;

class ProjectUpdatePolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user)
    {
        return $user->type === 'student' || $user->type === 'admin';
    }
}

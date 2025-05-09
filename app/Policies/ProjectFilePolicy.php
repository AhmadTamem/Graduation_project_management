<?php

namespace App\Policies;

use App\Models\User;

class ProjectFilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user)
    {
        return $user->type === 'student' || $user->type === 'admin';
    }
    public function destroy(User $user)
    {
        return $user->type === 'committee_head' || $user->type === 'admin'||$user->type === 'student';
    }
}

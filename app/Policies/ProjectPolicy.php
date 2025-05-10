<?php

namespace App\Policies;

use App\Models\User;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */

        public function create(User $user)
        {
            return $user->type === 'student' || $user->type === 'admin';
        }
        public function update(User $user)
        {
            return $user->type === 'committee_head' || $user->type=== 'admin';
        }
        public function index(User $user)
        {
            return $user->type === 'committee_head' || $user->type=== 'admin' || $user->type==='supervisor';
        }
        public function show(User $user)
        {
            return $user->type === 'committee_head' || $user->type=== 'admin' || $user->type==='supervisor' || $user->type === 'student';
        }
        
    

}

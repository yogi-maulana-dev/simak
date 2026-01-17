<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function superadminAccess(User $user): bool
    {
        return $user->role->name === 'superadmin';
    }
}
<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TokenPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function login()
    {
        return true;
    }

    public function logout()
    {
        return true;
    }

    public function impersonate(User $user = null, User $imp)
    {
        if (null === $user) {
            return (bool) env('APP_DEBUG', false);
        }

        return $user->isAllowed('impersonate') && $user->can('impersonate', $imp);
    }
}

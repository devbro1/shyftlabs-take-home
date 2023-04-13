<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $model
     * @param mixed            $id
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, $id)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user)
    {
        return $user->isAllowedAny(['Add User']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, User $model)
    {
        return $user->isAllowedAny(['Update User']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }

    public function getCurrentUser(User $user)
    {
        return true;
    }

    public function forgotPassword(User $user = null)
    {
        return true;
    }

    public function resetPassword(User $user = null)
    {
        return true;
    }

    public function register(User $user = null)
    {
        return true;
    }

    public function verifyEmail(User $user = null)
    {
        return true;
    }
}

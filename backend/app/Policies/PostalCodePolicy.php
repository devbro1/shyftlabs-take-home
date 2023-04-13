<?php

namespace App\Policies;

use App\Models\PostalCode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostalCodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function viewAny(User|null $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User|null $user, PostalCode $postalCode)
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
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, PostalCode $postalCode)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, PostalCode $postalCode)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, PostalCode $postalCode)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, PostalCode $postalCode)
    {
        return false;
    }
}

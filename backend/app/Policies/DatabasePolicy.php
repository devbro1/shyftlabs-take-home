<?php

namespace App\Policies;

use App\Models\Database;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DatabasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, Database|string $database)
    {
        return $user->isAllowedAny(['configuration']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user)
    {
        return $user->isAllowedAny(['configuration']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Database $database)
    {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Database $database)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Database $database)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Database $database)
    {
    }
}

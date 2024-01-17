<?php

namespace App\Policies;

use App\Models\Export;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function viewAny(User $user)
    {
        return $user->isAllowedAny(['view exports']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, Export $export)
    {
        return $user->isAllowedAny(['view exports']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user)
    {
        return $user->isAllowedAny(['create exports']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Export $export)
    {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Export $export)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Export $export)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Export $export)
    {
    }
}

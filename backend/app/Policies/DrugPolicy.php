<?php

namespace App\Policies;

use App\Models\Drug;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DrugPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function viewAny(User $user)
    {
        return $user->isAllowedAny(['view all drugs']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, Drug $drug)
    {
        return $user->isAllowedAny(['view all drugs']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user)
    {
        return $user->isAllowedAny(['create drug']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Drug $drug)
    {
        return $user->isAllowedAny(['create drug', 'update drug']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Drug $drug)
    {
        return $user->isAllowedAny(['delete drug']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Drug $drug)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Drug $drug)
    {
        return $user->isAllowedAny(['delete drug']);
    }

    public function audits(User $user, Drug $drug)
    {
        return $user->isAllowedAny(['view all drugs']);        
    }
}

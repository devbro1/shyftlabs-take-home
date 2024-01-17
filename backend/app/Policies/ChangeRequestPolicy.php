<?php

namespace App\Policies;

use App\Models\ChangeRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChangeRequestPolicy
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
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, ChangeRequest $changeRequest)
    {
        return $user->isAllowedAny(['view change-requests']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user)
    {
        return $user->isAllowedAny(['create change-requests']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, ChangeRequest $changeRequest)
    {
        if ('PENDING' != $changeRequest->status) {
            return false;
        }

        return $user->isAllowedAny(['update change-requests', 'decline change-requests', 'approve change-requests']);
    }

    public function next(User $user, ChangeRequest $changeRequest)
    {
        return $user->isAllowedAny(['update change-requests', 'decline change-requests', 'approve change-requests']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, ChangeRequest $changeRequest)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, ChangeRequest $changeRequest)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, ChangeRequest $changeRequest)
    {
    }
}

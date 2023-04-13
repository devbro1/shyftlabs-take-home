<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
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
    public function view(User $user, Appointment $appointment)
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
        $owner_id = request('owner_id', false);

        if (false === $owner_id || $owner_id === user()->id) {
            return $user->isAllowedAll(['have appointments', 'manage self appointments']);
        }

        $owner = User::find($owner_id);
        if (!$owner->isAllowed('have appointments')) {
            return 'owner_id is not allowed to have appointments';
        }

        return $user->isAllowedAny([
            'manage self appointments',
            'manage all appointments',
            'manage company appointments', ]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Appointment $appointment)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Appointment $appointment)
    {
        if ($appointment->owner_id === $user->id) {
            return true;
        }
        if ($appointment->created_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Appointment $appointment)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Appointment $appointment)
    {
        return false;
    }

    public function getUserAppointments(User $user, User $owner)
    {
        return true;
    }
}

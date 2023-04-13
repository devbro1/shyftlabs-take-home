<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\Action;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ActionPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $request = request();
        $lead = request('lead');
        // $lead = Lead::findOrFail($lead);
        Gate::authorize('view', $lead);
    }

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
     * @param \App\Models\Action $action
     * @param mixed              $action_id
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, Lead $lead, $action_id)
    {
        return $lead->status->actions->where('id', $action_id)->count() > 0;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Action $action
     * @param mixed              $action_id
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Lead $lead, $action_id)
    {
        return $lead->status->actions->where('id', $action_id)->count() > 0;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Action $action)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Action $action)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Action $action)
    {
    }
}

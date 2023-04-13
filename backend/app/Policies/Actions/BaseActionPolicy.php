<?php

namespace App\Policies\Actions;

use App\Models\Lead;
use App\Models\ActionWorkflowNode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BaseActionPolicy
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
     * @param \App\Models\Action $action
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, Lead $lead, ActionWorkflowNode $actionWorkflowNode)
    {
        if ($actionWorkflowNode->permission) {
            $user->forceAllow($actionWorkflowNode->permission_id);
        }

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
     * @param \App\Models\Action $action
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Lead $lead, ActionWorkflowNode $actionWorkflowNode)
    {
        if ($actionWorkflowNode->permission) {
            $user->forceAllow($actionWorkflowNode->permission_id);
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Action $action
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Lead $lead, ActionWorkflowNode $actionWorkflowNode)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Action $action
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Lead $lead, ActionWorkflowNode $actionWorkflowNode)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Action $action
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Lead $lead, ActionWorkflowNode $actionWorkflowNode)
    {
    }
}

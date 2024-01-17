<?php

namespace App\Policies;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TranslationPolicy
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
    public function view(User $user, Translation $translation)
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
        return $user->can('create translations');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Translation $translation)
    {
        return $user->can('update translations');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Translation $translation)
    {
        return $user->can('delete translations');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Translation $translation)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Translation $translation)
    {
        return $user->can('delete translations');
    }

    public function getCachedTranslation(User $user = null, $lang)
    {
        return true;
    }

    public function getNamespaces(User $user = null)
    {
        return true;
    }

    public function reportMissingTranslation(User $user = null, $lang, $namespace)
    {
        return true;
    }
}

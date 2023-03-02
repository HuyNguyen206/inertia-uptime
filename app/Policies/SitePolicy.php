<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;

class SitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Site $site): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Site $site): bool
    {
        return $user->sites()->where('sites.id', $site->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Site $site): bool
    {
        return $user->id === $site->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Site $site): bool
    {
        return $user->id === $site->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Site $site): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Site $site): bool
    {
        //
    }

    public function canGetLogs(User $user, Site $site)
    {
        return $user->id === $site->user_id;
    }

    public function canAddEmailNotification(User $user, Site $site)
    {
        return $user->id === $site->user_id;
    }

    public function canRemoveEmailNotification(User $user, Site $site)
    {
        return $user->id === $site->user_id;
    }
}

<?php

namespace App\Policies;

use App\Models\Mod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view mods');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Mod $mod): bool
    {
        if ($mod->status === 'approved' && $mod->published_at !== null) {
            return true;
        }

        // If not approved, user must be authenticated
        if (!$user) {
            return false;
        }

        // Owner can view their own mods regardless of status
        if ($user->id === $mod->user_id) {
            return true;
        }

        // Admins and moderators can view all
        return $user->hasPermissionTo('view mods');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isBanned()) {
            return false;
        }

        return $user->hasPermissionTo('create mods');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mod $mod): bool
    {
        if ($user->isBanned()) {
            return false;
        }

        if ($user->id === $mod->user_id) {
            return $user->hasPermissionTo('edit own mods');
        }

        return $user->hasPermissionTo('edit any mods');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mod $mod): bool
    {
        if ($user->isBanned()) {
            return false;
        }

        if ($user->id === $mod->user_id) {
            return $user->hasPermissionTo('delete own mods');
        }

        return $user->hasPermissionTo('delete any mods');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mod $mod): bool
    {
        return $user->hasPermissionTo('delete any mods');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mod $mod): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Mod $mod): bool
    {
        return $user->hasPermissionTo('approve mods');
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, Mod $mod): bool
    {
        return $user->hasPermissionTo('reject mods');
    }
}

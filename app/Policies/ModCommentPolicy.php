<?php

namespace App\Policies;

use App\Models\ModComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModCommentPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModComment $modComment): bool
    {
        // User owns the comment (only for registered users)
        if ($modComment->user_id && $user->id === $modComment->user_id) {
            return true;
        }

        // Mod owner owns the mod the comment is on (optional, but good for moderation)
        if ($user->id === $modComment->mod->user_id) {
            return true;
        }

        // Admins/Moderators
        return $user->hasPermissionTo('moderate comments');
    }
}

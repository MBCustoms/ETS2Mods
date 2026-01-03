<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view reports');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        return $user->hasPermissionTo('view reports') || $user->id === $report->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->isBanned();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->hasPermissionTo('resolve reports');
    }

    /**
     * Determine whether the user can resolve the report.
     */
    public function resolve(User $user, Report $report): bool
    {
        return $user->hasPermissionTo('resolve reports');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->hasRole('admin');
    }
}

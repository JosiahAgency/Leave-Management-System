<?php

namespace App\Policies;

use App\Models\Departments;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view departments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Departments $departments): bool
    {
        return $user->hasPermissionTo('view departments');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create departments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Departments $departments): bool
    {
        return $user->hasPermissionTo('update departments');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Departments $departments): bool
    {
        return $user->hasPermissionTo('delete departments');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Departments $departments): bool
    {
        return $user->hasPermissionTo('restore departments');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Departments $departments): bool
    {
        return $user->hasPermissionTo('forceDelete departments');
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolesPolicy
{
        /**
         * Determine whether the user can view any models.
         */
        public function viewAny(User $user): bool
        {
//        return $user->hasPermissionTo('view roles');
                return true;
        }


        /**
         * Determine whether the user can view the model.
         */
        public
        function view(User $user, Role $roles): bool
        {
//        return $user->hasPermissionTo('view roles');
                return true;
        }


        /**
         * Determine whether the user can create models.
         */
        public
        function create(User $user): bool
        {
//        return $user->hasPermissionTo('create roles');
                return true;
        }


        /**
         * Determine whether the user can update the model.
         */
        public
        function update(User $user, Role $roles): bool
        {
//        return $user->hasPermissionTo('update roles');
                return true;
        }


        /**
         * Determine whether the user can delete the model.
         */
        public
        function delete(User $user, Role $roles): bool
        {
                return $user->hasPermissionTo('delete roles');
        }

        /**
         * Determine whether the user can restore the model.
         */
        public
        function restore(User $user, Role $roles): bool
        {
                return $user->hasPermissionTo('restore roles');
        }

        /**
         * Determine whether the user can permanently delete the model.
         */
        public
        function forceDelete(User $user, Role $roles): bool
        {
                return $user->hasPermissionTo('forceDelete roles');
        }
}

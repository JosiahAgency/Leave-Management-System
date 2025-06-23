<?php

namespace App\Providers;

use App\Models\Departments;
use App\Models\LeaveType;
use App\Models\User;
use App\Policies\DepartmentsPolicy;
use App\Policies\Leavetypepolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolesPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(Role::class, RolesPolicy::class);
        Gate::policy(LeaveType::class, Leavetypepolicy::class);
        Gate::policy(Departments::class, DepartmentsPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
//        Gate::policy(Permission::class, PermissionPol::class);
    }
}

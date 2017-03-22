<?php

namespace App\Providers;

use App\Branch;
use App\Department;
use App\Policies\BranchPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\RolePolicy;
use App\Policies\SchedulePolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkplacePolicy;
use App\Role;
use App\Schedule;
use App\User;
use App\Workplace;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Branch::class => BranchPolicy::class,
        User::class => UserPolicy::class,
        Workplace::class => WorkplacePolicy::class,
        Department::class => DepartmentPolicy::class,
        Role::class => RolePolicy::class,
        Schedule::class => SchedulePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

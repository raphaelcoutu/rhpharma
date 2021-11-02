<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Constraint;
use App\Models\ConstraintType;
use App\Models\Department;
use App\Models\Holiday;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\User;
use App\Models\Workplace;
use App\Policies\BranchPolicy;
use App\Policies\ConstraintPolicy;
use App\Policies\ConstraintTypePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\HolidayPolicy;
use App\Policies\RolePolicy;
use App\Policies\SchedulePolicy;
use App\Policies\SettingPolicy;
use App\Policies\ShiftPolicy;
use App\Policies\ShiftTypePolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkplacePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Branch::class => BranchPolicy::class,
        Constraint::class => ConstraintPolicy::class,
        ConstraintType::class => ConstraintTypePolicy::class,
        Department::class => DepartmentPolicy::class,
        Holiday::class => HolidayPolicy::class,
        Role::class => RolePolicy::class,
        Schedule::class => SchedulePolicy::class,
        Setting::class => SettingPolicy::class,
        Shift::class => ShiftPolicy::class,
        ShiftType::class => ShiftTypePolicy::class,
        User::class => UserPolicy::class,
        Workplace::class => WorkplacePolicy::class,
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

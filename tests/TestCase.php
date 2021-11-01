<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $superUser;

    protected function createSuperUser(): void {
        $this->seed(PermissionSeeder::class);

        $this->superUser = User::factory()->create();
        $superUserRole = Role::create(['name' => 'SuperU', 'description' => '']);
        $this->superUser->roles()->sync($superUserRole);

        $permissions = Permission::all()->pluck('code');
        $superUserRole->permissions()->sync($permissions);
    }
}

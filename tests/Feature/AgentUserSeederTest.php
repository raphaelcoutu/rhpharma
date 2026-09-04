<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AgentUserSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_user_is_seeded_with_all_permissions(): void
    {
        $originalEnvironment = $this->app->environment();
        $this->app->detectEnvironment(fn (): string => 'local');

        try {
            Branch::create(['name' => 'Pharmaciens']);
            $this->seed(PermissionSeeder::class);
            $this->seed(AgentUserSeeder::class);

            $agentUser = User::with('roles.permissions')
                ->where('email', 'agent@example.test')
                ->firstOrFail();

            $agentPermissionCodes = $agentUser->roles
                ->flatMap(fn (Role $role) => $role->permissions)
                ->pluck('code')
                ->unique()
                ->sort()
                ->values()
                ->all();

            $permissionCodes = Permission::query()
                ->pluck('code')
                ->sort()
                ->values()
                ->all();

            $this->assertSame($permissionCodes, $agentPermissionCodes);
        } finally {
            $this->app->detectEnvironment(fn (): string => $originalEnvironment);
        }
    }
}

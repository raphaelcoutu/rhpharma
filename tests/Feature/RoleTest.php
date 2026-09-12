<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_unauth_user_cannot_see_roles()
    {
        $response = $this->get('/roles');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_role_cannot_see_roles(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get('/roles');

        $response->assertForbidden();
    }

    public function test_authenticated_user_can_edit_a_role(): void
    {
        $role = Role::create(['name' => 'Planner', 'description' => '']);

        $response = $this->actingAs($this->superUser)
            ->get("/roles/{$role->id}/edit");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('roles/edit', false)
            ->where('role.id', $role->id)
            ->where('role.name', 'Planner')
            ->has('permissions'));
    }

    public function test_authenticated_user_can_see_roles_and_permissions(): void
    {
        $role = Role::create(['name' => 'Planner', 'description' => 'Planification']);
        $permission = Permission::firstOrCreate(['code' => 'ReadSchedules']);
        $role->permissions()->attach($permission);

        $response = $this->actingAs($this->superUser)
            ->get('/roles');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('roles/index', false)
            ->where('roles.0.name', 'Planner')
            ->where('roles.0.permissions.0.code', 'ReadSchedules')
            ->has('permissions'));
    }

    public function test_authenticated_user_can_update_a_role_and_its_permissions(): void
    {
        $role = Role::create(['name' => 'Planner', 'description' => 'Ancienne description']);
        $permission = Permission::firstOrCreate(['code' => 'WriteSchedules']);

        $response = $this->actingAs($this->superUser)
            ->put("/roles/{$role->id}", [
                'name' => 'Planificateur',
                'description' => 'Gestion des horaires',
                'permissions' => [
                    'WriteSchedules' => true,
                ],
            ]);

        $response->assertRedirect('/roles');
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Planificateur',
            'description' => 'Gestion des horaires',
        ]);
        $this->assertTrue($role->fresh()->permissions->contains('code', $permission->code));
    }
}

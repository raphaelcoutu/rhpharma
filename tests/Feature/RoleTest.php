<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_authenticated_user_can_edit_a_role(): void
    {
        $role = Role::create(['name' => 'Planner', 'description' => '']);

        $response = $this->actingAs($this->superUser)
            ->get("/roles/{$role->id}/edit");

        $response->assertOk();
    }
}

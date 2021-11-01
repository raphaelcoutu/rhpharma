<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Branch;
use App\Models\Permission;
use Database\Seeders\PermissionSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchTest extends TestCase
{
    use RefreshDatabase;

    private $branch;

    public function setUp(): void {
        parent::setUp();
        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_auth_user_can_see_branches() {
        $response = $this->actingAs($this->superUser)->get('/branches');

        $response->assertStatus(200);
        $response->assertSee('Pharmaciens');
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/branches');

        $response->assertRedirect('/login');
    }
}

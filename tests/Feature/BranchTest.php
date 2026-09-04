<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BranchTest extends TestCase
{
    use RefreshDatabase;

    private $branch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_auth_user_can_see_branches()
    {
        $response = $this->actingAs($this->superUser)->get('/branches');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('branches/index', false)
            ->where('branches.0.name', 'Pharmaciens')
            ->where('branches.0.users_count', 1));
    }

    public function test_branches_can_be_reloaded_partially()
    {
        $response = $this->actingAs($this->superUser)->get(route('branches.index'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('branches/index')
            ->has('branches', 1)
            ->reloadOnly('branches', fn (Assert $reload) => $reload
                ->has('branches', 1)
                ->where('branches.0.name', 'Pharmaciens')));
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/branches');

        $response->assertRedirect('/login');
    }

    public function test_authorized_user_can_create_branch()
    {
        $response = $this->actingAs($this->superUser)
            ->post(route('branches.store'), ['name' => 'Nouvelle branche']);

        $response->assertRedirectToRoute('branches.index');
        $this->assertDatabaseHas('branches', ['name' => 'Nouvelle branche']);
    }

    public function test_authorized_user_can_update_branch()
    {
        $response = $this->actingAs($this->superUser)
            ->put(route('branches.update', $this->branch), ['name' => 'Branche modifiée']);

        $response->assertRedirectToRoute('branches.index');
        $this->assertDatabaseHas('branches', [
            'id' => $this->branch->id,
            'name' => 'Branche modifiée',
        ]);
    }

    public function test_branch_name_is_required_when_creating_branch()
    {
        $response = $this->actingAs($this->superUser)
            ->post(route('branches.store'), ['name' => 'x']);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('branches', ['name' => 'x']);
    }

    public function test_user_without_branch_write_permission_cannot_create_branch()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('branches.store'), ['name' => 'Nouvelle branche']);

        $response->assertForbidden();
        $this->assertDatabaseMissing('branches', ['name' => 'Nouvelle branche']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Branch;
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

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/branches');

        $response->assertRedirect('/login');
    }
}

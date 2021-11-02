<?php

namespace Tests\Feature;

use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

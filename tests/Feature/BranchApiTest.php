<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchApiTest extends TestCase
{
    use RefreshDatabase;

    private $branch;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->user = User::factory()->create();

    }

    public function test_get_branches()
    {
        $response = $this->actingAs($this->user)
            ->getAjax('/api/branches');

        $response->assertStatus(200);
        $response->assertJsonPath('0.name', 'Pharmaciens');
    }

    public function test_get_branch()
    {
        $response = $this->actingAs($this->user)
            ->getAjax('/api/branches/1');

        $response->assertStatus(200);
        $response->assertJsonPath('id', 1);
        $response->assertJsonPath('name', 'Pharmaciens');
    }

    public function test_post_branch()
    {
        $newBranch = ['name' => 'Nouvelle branche'];

        $response = $this->actingAs($this->user)
            ->postAjax('/api/branches/store', $newBranch);

        $response->assertStatus(200);
        $this->assertCount(2, Branch::all());
    }

    public function test_put_branch()
    {
        $branch = ['name' => 'Nouvelle branche'];

        $response = $this->actingAs($this->user)
            ->putAjax('/api/branches/1', $branch);

        $response->assertStatus(200);

        $updatedBranch = Branch::findOrFail(1);
        $this->assertEquals('Nouvelle branche', $updatedBranch->name);
    }
}

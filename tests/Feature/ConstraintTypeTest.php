<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\ConstraintType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ConstraintTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->createSuperUser();
    }

    public function test_auth_user_can_see_only_their_branch_constraint_types(): void
    {
        $constraintType = ConstraintType::create([
            'name' => 'Travail de jour',
            'description' => 'Une règle de jour',
            'code' => 'TRJ',
            'is_work' => true,
            'is_single_day' => true,
            'is_group_constraint' => false,
            'is_day_in_schedule' => true,
            'branch_id' => $this->branch->id,
        ]);
        $otherBranch = Branch::create(['name' => 'Assistants techniques']);
        ConstraintType::create([
            'name' => 'Autre branche',
            'description' => 'Ne doit pas apparaître',
            'code' => 'AUT',
            'is_work' => false,
            'is_single_day' => false,
            'is_group_constraint' => true,
            'is_day_in_schedule' => false,
            'branch_id' => $otherBranch->id,
        ]);

        $response = $this->actingAs($this->superUser)
            ->get('/constraint-types');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('constraint-types/index', false)
            ->has('constraintTypes', 1)
            ->where('constraintTypes.0.id', $constraintType->id)
            ->where('constraintTypes.0.name', 'Travail de jour')
            ->where('constraintTypes.0.criteria_count', 0));
    }

    public function test_auth_user_can_see_constraint_type_create_form(): void
    {
        $response = $this->actingAs($this->superUser)
            ->get('/constraint-types/create');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('constraint-types/create', false));
    }

    public function test_auth_user_can_see_constraint_type_edit_form(): void
    {
        $constraintType = ConstraintType::factory()->create([
            'branch_id' => $this->branch->id,
            'name' => 'Travail de jour',
            'code' => 'TRJ',
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/constraint-types/{$constraintType->id}/edit");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('constraint-types/edit', false)
            ->where('constraintType.id', $constraintType->id)
            ->where('constraintType.name', 'Travail de jour'));
    }

    public function test_auth_user_cannot_edit_a_constraint_type_from_another_branch(): void
    {
        $otherBranch = Branch::create(['name' => 'Assistants techniques']);
        $constraintType = ConstraintType::factory()->create(['branch_id' => $otherBranch->id]);

        $response = $this->actingAs($this->superUser)
            ->get("/constraint-types/{$constraintType->id}/edit");

        $response->assertNotFound();
    }

    public function test_auth_user_can_create_constraint_type(): void
    {
        $response = $this->actingAs($this->superUser)
            ->post('/constraint-types', [
                'azure_id' => 42,
                'name' => 'Travail de jour',
                'description' => 'Une règle de jour',
                'code' => 'TRJ',
                'is_work' => '1',
                'is_single_day' => '1',
                'is_group_constraint' => '0',
                'is_day_in_schedule' => '1',
            ]);

        $response->assertRedirect('/constraint-types');
        $this->assertDatabaseHas('constraint_types', [
            'azure_id' => 42,
            'name' => 'Travail de jour',
            'code' => 'TRJ',
            'branch_id' => $this->branch->id,
        ]);
    }

    public function test_auth_user_can_update_constraint_type(): void
    {
        $constraintType = ConstraintType::factory()->create([
            'branch_id' => $this->branch->id,
            'name' => 'Travail de jour',
            'code' => 'TRJ',
        ]);

        $response = $this->actingAs($this->superUser)
            ->put("/constraint-types/{$constraintType->id}", [
                'name' => 'Travail prolongé',
                'description' => 'Une nouvelle règle',
                'code' => 'TRP',
                'is_work' => '1',
                'is_single_day' => '0',
                'is_group_constraint' => '1',
                'is_day_in_schedule' => '1',
            ]);

        $response->assertRedirect('/constraint-types');
        $this->assertDatabaseHas('constraint_types', [
            'id' => $constraintType->id,
            'name' => 'Travail prolongé',
            'code' => 'TRP',
            'is_group_constraint' => true,
        ]);
    }

    public function test_auth_user_cannot_update_a_constraint_type_from_another_branch(): void
    {
        $otherBranch = Branch::create(['name' => 'Assistants techniques']);
        $constraintType = ConstraintType::factory()->create([
            'branch_id' => $otherBranch->id,
            'name' => 'Original',
        ]);

        $response = $this->actingAs($this->superUser)
            ->put("/constraint-types/{$constraintType->id}", [
                'name' => 'Modification interdite',
                'description' => 'Ne doit pas être enregistrée',
                'code' => 'NOPE',
                'is_work' => '1',
                'is_single_day' => '1',
                'is_group_constraint' => '0',
                'is_day_in_schedule' => '0',
            ]);

        $response->assertNotFound();
        $this->assertDatabaseHas('constraint_types', [
            'id' => $constraintType->id,
            'name' => 'Original',
        ]);
    }

    public function test_constraint_type_creation_requires_the_legacy_form_fields(): void
    {
        $response = $this->actingAs($this->superUser)
            ->from('/constraint-types/create')
            ->post('/constraint-types', []);

        $response->assertSessionHasErrors([
            'name',
            'code',
            'is_work',
            'is_single_day',
            'is_group_constraint',
            'is_day_in_schedule',
        ]);
    }

    public function test_unauthenticated_user_is_redirected_from_constraint_types(): void
    {
        $response = $this->get('/constraint-types');

        $response->assertRedirect('/login');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\ShiftType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShiftTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->createSuperUser();
    }

    public function test_auth_user_can_see_only_their_branch_shift_types(): void
    {
        $shiftType = ShiftType::create([
            'name' => 'Jour',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'branch_id' => $this->branch->id,
        ]);
        $otherBranch = Branch::create(['name' => 'Assistants techniques']);
        ShiftType::create([
            'name' => 'Autre branche',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'branch_id' => $otherBranch->id,
        ]);

        $response = $this->actingAs($this->superUser)
            ->get('/shift-types');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('shift-types/index', false)
            ->has('shiftTypes', 1)
            ->where('shiftTypes.0.id', $shiftType->id)
            ->where('shiftTypes.0.name', 'Jour'));
    }

    public function test_auth_user_can_see_shift_type_create_form(): void
    {
        $response = $this->actingAs($this->superUser)
            ->get('/shift-types/create');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('shift-types/create', false));
    }

    public function test_auth_user_can_see_shift_type_edit_form(): void
    {
        $shiftType = ShiftType::create([
            'name' => 'Jour',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'branch_id' => $this->branch->id,
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/shift-types/{$shiftType->id}/edit");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('shift-types/edit', false)
            ->where('shiftType.id', $shiftType->id)
            ->where('shiftType.name', 'Jour'));
    }

    public function test_auth_user_cannot_edit_a_shift_type_from_another_branch(): void
    {
        $otherBranch = Branch::create(['name' => 'Assistants techniques']);
        $shiftType = ShiftType::create([
            'name' => 'Autre branche',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'branch_id' => $otherBranch->id,
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/shift-types/{$shiftType->id}/edit");

        $response->assertNotFound();
    }

    public function test_auth_user_can_create_shift_type(): void
    {
        $response = $this->actingAs($this->superUser)
            ->post('/shift-types', [
                'name' => 'Soir',
                'start_time' => '16:00:00',
                'end_time' => '00:00:00',
            ]);

        $response->assertRedirect('/shift-types');
        $this->assertDatabaseHas('shift_types', [
            'name' => 'Soir',
            'start_time' => '16:00:00',
            'end_time' => '00:00:00',
            'branch_id' => $this->branch->id,
        ]);
    }

    public function test_auth_user_can_update_shift_type(): void
    {
        $shiftType = ShiftType::create([
            'name' => 'Jour',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'branch_id' => $this->branch->id,
        ]);

        $response = $this->actingAs($this->superUser)
            ->post("/shift-types/{$shiftType->id}", [
                'name' => 'Jour prolongé',
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
            ]);

        $response->assertRedirect('/shift-types');
        $this->assertDatabaseHas('shift_types', [
            'id' => $shiftType->id,
            'name' => 'Jour prolongé',
            'end_time' => '18:00:00',
        ]);
    }

    public function test_unauthenticated_user_is_redirected_from_shift_types(): void
    {
        $response = $this->get('/shift-types');

        $response->assertRedirect('/login');
    }
}

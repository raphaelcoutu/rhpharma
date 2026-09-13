<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShiftTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->createSuperUser();
        $this->superUser->update(['branch_id' => $this->branch->id]);
    }

    public function test_auth_user_can_see_only_their_branch_shifts(): void
    {
        $shift = $this->createShift($this->branch, 'J');
        $otherBranch = Branch::create(['name' => 'Assistants techniques']);
        $this->createShift($otherBranch, 'AUTRE');

        $response = $this->actingAs($this->superUser)
            ->get('/shifts');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('shifts/index', false)
            ->has('shifts', 1)
            ->where('shifts.0.id', $shift->id)
            ->where('shifts.0.code', 'J')
            ->where('shifts.0.department.name', 'Soins intensifs')
            ->where('shifts.0.shift_type.name', 'Jour'));
    }

    public function test_auth_user_can_see_shift_create_form(): void
    {
        $response = $this->actingAs($this->superUser)
            ->get('/shifts/create');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('shifts/create', false)
            ->has('departments', 0)
            ->has('shiftTypes', 0));
    }

    public function test_auth_user_can_see_shift_edit_form(): void
    {
        $shift = $this->createShift($this->branch, 'J');

        $response = $this->actingAs($this->superUser)
            ->get("/shifts/{$shift->id}/edit");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('shifts/edit', false)
            ->where('shift.id', $shift->id)
            ->where('shift.code', 'J'));
    }

    public function test_auth_user_cannot_edit_a_shift_from_another_branch(): void
    {
        $otherBranch = Branch::create(['name' => 'Assistants techniques']);
        $shift = $this->createShift($otherBranch, 'AUTRE');

        $response = $this->actingAs($this->superUser)
            ->get("/shifts/{$shift->id}/edit");

        $response->assertNotFound();
    }

    public function test_auth_user_can_create_a_shift(): void
    {
        $department = $this->createDepartment($this->branch, 'Nouveaux soins');
        $shiftType = $this->createShiftType($this->branch, 'Soir');

        $response = $this->actingAs($this->superUser)
            ->post('/shifts', [
                'code' => 'S',
                'department_id' => $department->id,
                'shift_type_id' => $shiftType->id,
            ]);

        $response->assertRedirect('/shifts');
        $this->assertDatabaseHas('shifts', [
            'code' => 'S',
            'department_id' => $department->id,
            'shift_type_id' => $shiftType->id,
            'description' => '',
            'is_default' => false,
        ]);
    }

    public function test_auth_user_can_update_a_shift(): void
    {
        $shift = $this->createShift($this->branch, 'J');
        $department = $this->createDepartment($this->branch, 'Soins prolongés');
        $shiftType = $this->createShiftType($this->branch, 'Nuit');

        $response = $this->actingAs($this->superUser)
            ->post("/shifts/{$shift->id}", [
                'code' => 'N',
                'department_id' => $department->id,
                'shift_type_id' => $shiftType->id,
            ]);

        $response->assertRedirect('/shifts');
        $this->assertDatabaseHas('shifts', [
            'id' => $shift->id,
            'code' => 'N',
            'department_id' => $department->id,
            'shift_type_id' => $shiftType->id,
        ]);
    }

    public function test_unauthenticated_user_is_redirected_from_shifts(): void
    {
        $response = $this->get('/shifts');

        $response->assertRedirect('/login');
    }

    private function createShift(Branch $branch, string $code): Shift
    {
        return Shift::create([
            'code' => $code,
            'description' => '',
            'is_default' => false,
            'department_id' => $this->createDepartment($branch)->id,
            'shift_type_id' => $this->createShiftType($branch)->id,
        ]);
    }

    private function createDepartment(Branch $branch, string $name = 'Soins intensifs'): Department
    {
        $workplace = Workplace::create(['name' => 'Hôpital '.$branch->id.' '.$name]);

        return Department::create([
            'name' => $name,
            'description' => '',
            'branch_id' => $branch->id,
            'workplace_id' => $workplace->id,
            'bonus_weeks' => 1,
            'bonus_pts' => 1,
            'malus_weeks' => 1,
            'malus_pts' => 1,
            'monday_am' => 1,
            'monday_pm' => 1,
            'tuesday_am' => 1,
            'tuesday_pm' => 1,
            'wednesday_am' => 1,
            'wednesday_pm' => 1,
            'thursday_am' => 1,
            'thursday_pm' => 1,
            'friday_am' => 1,
            'friday_pm' => 1,
        ]);
    }

    private function createShiftType(Branch $branch, string $name = 'Jour'): ShiftType
    {
        return ShiftType::create([
            'name' => $name,
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'branch_id' => $branch->id,
        ]);
    }
}

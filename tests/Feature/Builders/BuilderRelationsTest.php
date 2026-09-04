<?php

namespace Tests\Feature\Builders;

use App\Models\AssignedShift;
use App\Models\Branch;
use App\Models\Constraint;
use App\Models\ConstraintType;
use App\Models\Department;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BuilderRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_builder_relations_load_their_related_models(): void
    {
        $branch = Branch::create(['name' => 'Pharmaciens']);
        $workplace = Workplace::factory()->create();
        $user = User::factory()->create(['branch_id' => $branch->id]);
        $department = Department::factory()->create([
            'branch_id' => $branch->id,
            'workplace_id' => $workplace->id,
        ]);
        $user->departments()->attach($department, ['active' => true]);

        $shiftType = ShiftType::create([
            'name' => 'Matin',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'branch_id' => $branch->id,
        ]);
        $shift = Shift::create([
            'shift_type_id' => $shiftType->id,
            'department_id' => $department->id,
            'code' => 'MAT',
            'description' => 'Quart de matin',
            'is_default' => true,
        ]);
        $assignedShift = AssignedShift::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'is_generated' => true,
            'is_published' => false,
            'date' => Carbon::today(),
        ]);
        $constraintType = ConstraintType::create([
            'name' => 'Absence',
            'description' => 'Absence',
            'code' => 'ABS',
            'is_work' => false,
            'is_single_day' => true,
            'is_group_constraint' => false,
            'is_day_in_schedule' => false,
            'branch_id' => $branch->id,
        ]);
        Constraint::create([
            'user_id' => $user->id,
            'start_datetime' => Carbon::today()->startOfDay(),
            'end_datetime' => Carbon::today()->endOfDay(),
            'constraint_type_id' => $constraintType->id,
            'weight' => false,
            'status' => 1,
        ]);

        $loadedAssignedShift = AssignedShift::with(['shift.shiftType', 'user.constraints'])
            ->findOrFail($assignedShift->id);
        $loadedDepartment = Department::with('users')->findOrFail($department->id);

        $this->assertSame($shift->id, $loadedAssignedShift->shift->id);
        $this->assertSame($shiftType->id, $loadedAssignedShift->shift->shiftType->id);
        $this->assertSame($user->id, $loadedAssignedShift->user->id);
        $this->assertCount(1, $loadedAssignedShift->user->constraints);
        $this->assertSame($user->id, $loadedDepartment->users->first()->id);
        $this->assertSame('08:00', $shiftType->start_time_string);
    }
}

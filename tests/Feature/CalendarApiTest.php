<?php

namespace Tests\Feature;

use App\Models\AssignedShift;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CalendarApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->workplace = Workplace::factory()->create(['name' => 'CHUS']);
        $this->user = User::factory()->create([
            'is_active' => 1,
        ]);
        $this->createSuperUser();

        $this->schedule = Schedule::factory()->create([
            'start_date' => Carbon::parse('2021-10-31'),
            'end_date' => Carbon::parse('2021-11-27'),
        ]);
    }

    public function test_get_user_data()
    {
        $request = [
            'userId' => 1,
            'date' => 20211101,
        ];

        $response = $this->actingAs($this->user)
            ->getAjax("/api/calendar/get-user-data?userId={$request['userId']}&date={$request['date']}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'assignedShifts', 'constraints', 'shifts']);
    }

    public function test_authorized_user_can_replace_one_day_shifts(): void
    {
        $shift = $this->createShift('J');

        $response = $this->actingAs($this->superUser)
            ->postAjax('/api/calendar/set-user-data', [
                'schedule_id' => $this->schedule->id,
                'user_id' => $this->user->id,
                'date' => '2021-11-01',
                'shifts' => [$shift->id],
            ]);

        $response->assertOk()->assertJsonCount(1);
        $this->assertDatabaseHas('assigned_shifts', [
            'user_id' => $this->user->id,
            'shift_id' => $shift->id,
            'date' => '2021-11-01',
        ]);
    }

    public function test_authorized_user_can_replace_multiple_selected_days(): void
    {
        $secondUser = User::factory()->create(['firstname' => 'Deuxième']);
        $shift = $this->createShift('S');

        $response = $this->actingAs($this->superUser)
            ->postAjax('/api/calendar/set-selected-data', [
                'schedule_id' => $this->schedule->id,
                'selected' => [
                    ['user_id' => $this->user->id, 'date' => '2021-11-01'],
                    ['user_id' => $secondUser->id, 'date' => '2021-11-02'],
                ],
                'shifts' => [$shift->id],
            ]);

        $response->assertOk()->assertJsonCount(2);
        $this->assertSame(2, AssignedShift::query()->where('shift_id', $shift->id)->count());
    }

    public function test_calendar_rejects_a_shift_outside_the_schedule_period(): void
    {
        $shift = $this->createShift('N');

        $response = $this->actingAs($this->superUser)
            ->postAjax('/api/calendar/set-user-data', [
                'schedule_id' => $this->schedule->id,
                'user_id' => $this->user->id,
                'date' => '2021-12-01',
                'shifts' => [$shift->id],
            ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('date');
    }

    private function createShift(string $code): Shift
    {
        $department = Department::factory()->create([
            'branch_id' => $this->branch->id,
            'workplace_id' => $this->workplace->id,
        ]);
        $shiftType = ShiftType::forceCreate([
            'name' => 'Type '.$code,
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'branch_id' => $this->branch->id,
        ]);

        return Shift::create([
            'shift_type_id' => $shiftType->id,
            'department_id' => $department->id,
            'code' => $code,
            'description' => 'Shift '.$code,
            'is_default' => true,
        ]);
    }
}

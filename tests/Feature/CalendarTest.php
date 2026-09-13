<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->workplace = Workplace::factory()->create(['name' => 'CHUS']);

        $this->createSuperUser();

        $this->schedule = Schedule::factory()->create([
            'name' => 'Prochain horaire',
            'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
            'limit_date' => Carbon::now()->addWeek()->next('Friday'),
            'start_date' => Carbon::now()->addWeek()->next('Sunday'),
            'end_date' => Carbon::now()->addWeeks(5)->next('Saturday')->setTime(23, 59, 59),
        ]);
    }

    public function test_auth_user_can_view_calendar()
    {
        $response = $this->actingAs($this->superUser)
            ->get("/calendar/{$this->schedule->id}");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('calendar', false)
            ->where('schedule.id', $this->schedule->id)
            ->has('users')
            ->has('departments')
            ->has('shifts')
            ->where('canEdit', true));
    }

    public function test_auth_user_can_view_calendar_by_single_department()
    {
        $departmentA = Department::factory()->create([
            'name' => 'Department A',
            'branch_id' => $this->branch->id,
            'workplace_id' => $this->workplace->id,
        ]);

        $departmentB = Department::factory()->create([
            'name' => 'Department B',
            'branch_id' => $this->branch->id,
            'workplace_id' => $this->workplace->id,
        ]);

        $userWithDepartmentA = User::factory()->create(['firstname' => 'UserA']);
        $userWithDepartmentA->departments()->syncWithoutDetaching([$departmentA->id]);

        $userWithDepartmentB = User::factory()->create(['firstname' => 'UserB']);
        $userWithDepartmentB->departments()->syncWithoutDetaching([$departmentB->id]);

        $userWithDepartmentAB = User::factory()->create(['firstname' => 'UserAB']);
        $userWithDepartmentAB->departments()->syncWithoutDetaching([$departmentA->id, $departmentB->id]);

        $response = $this->actingAs($this->superUser)
            ->get("/calendar/{$this->schedule->id}/by-department/{$departmentA->id}");

        $response->assertStatus(200);
        $response->assertSee('UserA');
        $response->assertSee('UserAB');
        $response->assertDontSee('UserB');
    }

    public function test_auth_user_can_view_calendar_by_multiple_departments()
    {
        $departmentA = Department::factory()->create([
            'name' => 'Department A',
            'branch_id' => $this->branch->id,
            'workplace_id' => $this->workplace->id,
        ]);

        $departmentB = Department::factory()->create([
            'name' => 'Department B',
            'branch_id' => $this->branch->id,
            'workplace_id' => $this->workplace->id,
        ]);

        $departmentC = Department::factory()->create([
            'name' => 'Department C',
            'branch_id' => $this->branch->id,
            'workplace_id' => $this->workplace->id,
        ]);

        $userWithDepartmentA = User::factory()->create(['firstname' => 'UserA']);
        $userWithDepartmentA->departments()->syncWithoutDetaching([$departmentA->id]);

        $userWithDepartmentB = User::factory()->create(['firstname' => 'UserB']);
        $userWithDepartmentB->departments()->syncWithoutDetaching([$departmentB->id]);

        $userWithDepartmentAB = User::factory()->create(['firstname' => 'UserAB']);
        $userWithDepartmentAB->departments()->syncWithoutDetaching([$departmentA->id, $departmentB->id]);

        $userWithDepartmentC = User::factory()->create(['firstname' => 'UserC']);
        $userWithDepartmentC->departments()->syncWithoutDetaching([$departmentC->id]);

        $response = $this->actingAs($this->superUser)
            ->get("/calendar/{$this->schedule->id}/by-department/{$departmentA->id},{$departmentB->id}");

        $response->assertStatus(200);
        $response->assertSee('UserA');
        $response->assertSee('UserB');
        $response->assertSee('UserAB');
        $response->assertDontSee('UserC');
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get("/calendar/{$this->schedule->id}");

        $response->assertRedirect('/login');
    }

    public function test_scheduler_prototype_route_is_removed(): void
    {
        $response = $this->actingAs($this->superUser)->get('/scheduler');

        $response->assertNotFound();
    }
}

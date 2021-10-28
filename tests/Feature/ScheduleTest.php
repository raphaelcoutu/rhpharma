<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Workplace;
use App\Models\Department;
use App\Models\Permission;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $workplace;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->seed(PermissionSeeder::class);

        $this->user = User::factory()->create();
        $this->user->permissions()->saveMany(Permission::all());
    }

    public function test_auth_user_can_see_schedules()
    {
        Schedule::factory()->create();

        $response = $this->actingAs($this->user)
            ->get('/schedules');

        $response->assertStatus(200);
        $response->assertSee('Horaires');
    }

    public function test_auth_user_can_see_schedules_create_form()
    {
        $response = $this->actingAs($this->user)
            ->get("/schedules/create");

        $response->assertStatus(200);
    }

    // public function test_auth_user_can_create_department()
    // {
    //     $response = $this->actingAs($this->user)
    //         ->post('/schedules', [
    //             'name' => 'Prochain horaire',
    //             'branch_id' => $this->workplace->id,
    //             'limit_date_weekends' => Carbon::now()->previous('Friday'),
    //             'limit_date' => Carbon::now()->previous('Friday'),
    //             'start_date' => Carbon::now()->next('Sunday'),
    //             'end_date' => Carbon::now()->addWeeks(4)->next('Saturday')->setTime(23,59,59),
    //             'branch_id' => 1,
    //             'status_holidays' => BuildStatus::Standby,
    //             'status_weekends' => BuildStatus::Standby,
    //             'status_last_evening' => BuildStatus::Standby,
    //             'status_clinical_departments' => BuildStatus::Standby,
    //             'notes' => null,
    //         ]);

    //     $response->assertRedirect('/departments');
    //     $this->assertNotNull(Department::where('name', 'Soins intensifs')->get());
    //     $this->assertCount(1, Department::all());
    // }

    // public function test_auth_user_can_see_department_edit_form()
    // {
    //     $department = Department::factory()->create([
    //         'name' => 'Soins intensifs'
    //     ]);

    //     $response = $this->actingAs($this->user)
    //         ->get("/departments/{$department->id}/edit");

    //     $response->assertStatus(200);
    // }

    // public function test_auth_user_can_edit_department()
    // {
    //     $department = Department::factory()->create([
    //         'name' => 'Soins intensifs'
    //     ]);

    //     $response = $this->actingAs($this->user)
    //         ->put("/departments/{$department->id}", [
    //             'id' => $department->id,
    //             'name' => 'Soins intensifs médicaux',
    //             'workplace_id' => $this->workplace->id,
    //             'bonus_weeks' => 2,
    //             'bonus_pts' => 4,
    //             'malus_weeks' => 3,
    //             'malus_pts' => 8,
    //             'monday_am' => 2,
    //             'monday_pm' => 2,
    //             'tuesday_am' => 2,
    //             'tuesday_pm' => 2,
    //             'wednesday_am' => 2,
    //             'wednesday_pm' => 2,
    //             'thursday_am' => 2,
    //             'thursday_pm' => 2,
    //             'friday_am' => 2,
    //             'friday_pm' => 2
    //         ]);

    //     $response->assertRedirect("/departments");
    // }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/schedules');

        $response->assertRedirect('/login');
    }
}

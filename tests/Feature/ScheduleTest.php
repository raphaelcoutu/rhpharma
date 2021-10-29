<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Workplace;
use App\Models\Department;
use App\Models\Permission;
use App\Builders\BuildStatus;
use Illuminate\Support\Carbon;
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

    public function test_auth_user_can_create_schedule()
    {
        $response = $this->actingAs($this->user)
            ->post('/schedules', [
                'name' => 'Prochain horaire',
                'branch_id' => $this->branch->id,
                'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
                'limit_date' => Carbon::now()->addWeek()->next('Friday'),
                'start_date' => Carbon::now()->addWeek()->next('Sunday'),
                'end_date' => Carbon::now()->addWeeks(5)->next('Saturday')->setTime(23,59,59),
                'branch_id' => 1,
                'status_holidays' => BuildStatus::Standby,
                'status_weekends' => BuildStatus::Standby,
                'status_last_evening' => BuildStatus::Standby,
                'status_clinical_departments' => BuildStatus::Standby,
                'notes' => null,
            ]);

        $response->assertRedirect('/schedules');
        $this->assertNotNull(Schedule::where('name', 'Prochain horaire')->get());
        $this->assertCount(1, Schedule::all());
    }

    public function test_auth_user_can_see_schedule_edit_form()
    {
        $schedule = Schedule::factory()->create([
            'name' => 'Prochain horaire',
            'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
            'limit_date' => Carbon::now()->addWeek()->next('Friday'),
            'start_date' => Carbon::now()->addWeek()->next('Sunday'),
            'end_date' => Carbon::now()->addWeeks(5)->next('Saturday')->setTime(23,59,59)
        ]);

        $response = $this->actingAs($this->user)
            ->get("/schedules/{$schedule->id}/edit");

        $response->assertStatus(200);
    }

    public function test_auth_user_can_edit_schedule()
    {
        $schedule = Schedule::factory()->create([
            'name' => 'Prochain horaire',
            'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
            'limit_date' => Carbon::now()->addWeek()->next('Friday'),
            'start_date' => Carbon::now()->addWeek()->next('Sunday'),
            'end_date' => Carbon::now()->addWeeks(5)->next('Saturday')->setTime(23,59,59)
        ]);

        $response = $this->actingAs($this->user)
            ->put("/schedules/{$schedule->id}", [
                'id' => $schedule->id,
                'name' => 'Prochain horaire',
                'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
                'limit_date' => Carbon::now()->addWeeks(2)->next('Friday'),
                'start_date' => Carbon::now()->addWeeks(2)->next('Sunday'),
                'end_date' => Carbon::now()->addWeeks(7)->next('Saturday')->setTime(23,59,59)
            ]);

        $response->assertRedirect("/schedules");
        $this->assertEquals(Carbon::now()->addWeeks(2)->next('Sunday'), Schedule::findOrFail($schedule->id)->start_date);
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/schedules');

        $response->assertRedirect('/login');
    }
}

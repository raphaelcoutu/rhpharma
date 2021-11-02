<?php

namespace Tests\Feature;

use App\Builders\BuildStatus;
use App\Models\Branch;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    private $workplace;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_auth_user_can_see_schedules()
    {
        Schedule::factory()->create();

        $response = $this->actingAs($this->superUser)
            ->get('/schedules');

        $response->assertStatus(200);
        $response->assertSee('Horaires');
    }

    public function test_auth_user_can_see_schedules_create_form()
    {
        $response = $this->actingAs($this->superUser)
            ->get("/schedules/create");

        $response->assertStatus(200);
    }

    public function test_auth_user_can_create_schedule()
    {
        $response = $this->actingAs($this->superUser)
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

        $response = $this->actingAs($this->superUser)
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

        $response = $this->actingAs($this->superUser)
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

<?php

namespace Tests\Feature;

use App\Events\BuildMessageGenerated;
use App\Events\UpdateBuildStatus;
use App\Jobs\CompleteWeekendsAndDaysOff;
use App\Jobs\GenerateStatsByDepartments;
use App\Jobs\ResetClinicalDepartments;
use App\Models\Branch;
use App\Models\BuildMessage;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BuildPollingTest extends TestCase
{
    use RefreshDatabase;

    private Schedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        Branch::create(['name' => 'Pharmaciens']);
        $this->createSuperUser();
        $this->schedule = Schedule::factory()->create();
    }

    public function test_schedule_page_exposes_polling_state(): void
    {
        BuildMessage::create([
            'schedule_id' => $this->schedule->id,
            'message' => 'Génération en préparation...',
        ]);

        $response = $this->actingAs($this->superUser)
            ->get(route('schedules.show', $this->schedule));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('schedules/show', false)
            ->has('buildMessages', 1)
            ->where('buildMessages.0.message', 'Génération en préparation...')
            ->where('statisticsStatus', 0));
    }

    public function test_status_update_persists_status_and_dispatches_the_job(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->superUser)
            ->postJson('/api/schedules/update-status', [
                'scheduleId' => $this->schedule->id,
                'buildStep' => 'weekends',
                'status' => 3,
            ]);

        $response->assertStatus(202);
        $this->assertDatabaseHas('schedules', [
            'id' => $this->schedule->id,
            'status_weekends' => 3,
        ]);
        Queue::assertPushed(CompleteWeekendsAndDaysOff::class, fn (CompleteWeekendsAndDaysOff $job): bool => $job->event->scheduleId === $this->schedule->id && $job->connection === null
        );
    }

    public function test_build_messages_are_persisted_for_polling(): void
    {
        event(new BuildMessageGenerated($this->schedule, 'Étape terminée.'));

        $this->assertDatabaseHas('build_messages', [
            'schedule_id' => $this->schedule->id,
            'message' => 'Étape terminée.',
        ]);
        $this->assertDatabaseCount('build_messages', 1);
    }

    public function test_error_status_messages_are_persisted_for_polling(): void
    {
        event(new UpdateBuildStatus($this->schedule->id, 'clinical', 2, 'No id in this department: 11'));

        $this->assertDatabaseHas('build_messages', [
            'schedule_id' => $this->schedule->id,
            'message' => 'Erreur: No id in this department: 11',
        ]);
    }

    public function test_clinical_reset_is_queued_and_persisted_for_polling(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->superUser)
            ->postJson('/api/schedules/update-status', [
                'scheduleId' => $this->schedule->id,
                'buildStep' => 'clinical',
                'status' => 5,
            ]);

        $response->assertStatus(202);
        $this->assertDatabaseHas('schedules', [
            'id' => $this->schedule->id,
            'status_clinical_departments' => 5,
        ]);
        Queue::assertPushed(ResetClinicalDepartments::class, fn (ResetClinicalDepartments $job): bool => $job->connection === null);
    }

    public function test_statistics_generation_marks_the_schedule_as_running_and_dispatches_the_job(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->superUser)
            ->get('/api/schedule-stat-department/'.$this->schedule->id.'/create');

        $response->assertStatus(202);
        $this->assertDatabaseHas('schedules', [
            'id' => $this->schedule->id,
            'status_statistics' => 3,
        ]);
        Queue::assertPushed(GenerateStatsByDepartments::class, 1);
    }
}

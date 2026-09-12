<?php

namespace Tests\Feature;

use App\Jobs\GenerateStatsByDepartments;
use App\Models\Branch;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ScheduleStatDepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_dispatch_department_statistics_job(): void
    {
        Branch::create(['name' => 'Pharmaciens']);
        $this->createSuperUser();
        $schedule = Schedule::factory()->create();

        Queue::fake([GenerateStatsByDepartments::class]);

        $response = $this->actingAs($this->superUser)
            ->get('/api/scheduleStatDepartment/'.$schedule->id.'/create');

        $response->assertStatus(202);
        Queue::assertPushed(GenerateStatsByDepartments::class, fn (GenerateStatsByDepartments $job): bool => $job->connection === null);
    }
}

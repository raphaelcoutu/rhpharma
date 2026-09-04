<?php

namespace Tests\Feature;

use App\Jobs\GenerateStatsByDepartments;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ScheduleStatDepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_dispatch_department_statistics_job(): void
    {
        Branch::create(['name' => 'Pharmaciens']);
        $user = User::factory()->create();

        Queue::fake([GenerateStatsByDepartments::class]);

        $response = $this->actingAs($user)
            ->get('/api/scheduleStatDepartment/1/create');

        $response->assertOk();
        Queue::assertPushed(GenerateStatsByDepartments::class, 1);
    }
}

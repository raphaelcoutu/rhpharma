<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CalendarApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->user = User::factory()->create([
            'is_active' => 1
        ]);

        Schedule::factory()->create([
            'start_date' => Carbon::parse('2021-10-31'),
            'end_date' => Carbon::parse('2021-11-27'),
        ]);
    }

    public function test_get_user_data()
    {
        $request = [
            'userId' => 1,
            'date' => 20211101
        ];

        $response = $this->actingAs($this->user)
            ->getAjax("/api/calendar/getUserData?userId={$request['userId']}&date={$request['date']}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'assignedShifts', 'constraints', 'shifts']);
    }
}

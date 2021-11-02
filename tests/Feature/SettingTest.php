<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\ConstraintType;
use App\Models\Department;
use App\Models\Setting;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    private $workplace;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();

        $this->workplace = Workplace::factory()->create([
            'name' => 'CHUS HF',
            'code' => 'HF',
            'address' => '12e Ave Nord',
            'city' => 'Sherbrooke'
        ]);
    }

    public function test_can_see_general_settings_page()
    {
        $departments = Department::factory()->count(2)->create();

        $values = [
            ['id' => $departments[0], 'active' => true, 'order' => 0],
            ['id' => $departments[1], 'active' => true, 'order' => 1],
        ];

        Setting::updateOrCreate(['key' => 'departments_order'], ['value' => json_encode($values)]);
        Setting::updateOrCreate(['key' => 'triplets_order'], ['value' => json_encode([])]);

        $response = $this->actingAs($this->superUser)
            ->get("/settings");

        $response->assertStatus(200);
    }

    public function test_can_see_constraint_type_settings_page()
    {
        $departments = ConstraintType::factory()->count(5)->create();

        $response = $this->actingAs($this->superUser)
            ->get("/settings/constraintTypes");

        $response->assertStatus(200);
    }

    public function test_can_see_departments_settings_page()
    {
        $departments = Department::factory()->count(5)->create();

        $response = $this->actingAs($this->superUser)
            ->get("/settings/departments");

        $response->assertStatus(200);
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/settings');

        $response->assertRedirect('/login');
    }
}

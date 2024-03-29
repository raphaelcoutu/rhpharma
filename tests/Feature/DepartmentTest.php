<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
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

    public function test_auth_user_can_see_departments()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/departments');

        $response->assertStatus(200);
        $response->assertSee('Ajouter un secteur');
    }

    public function test_auth_user_can_see_department_create_form()
    {
        $response = $this->actingAs($this->superUser)
            ->get("/departments/create");

        $response->assertStatus(200);
    }

    public function test_auth_user_can_create_department()
    {
        $response = $this->actingAs($this->superUser)
            ->post('/departments', [
                'name' => 'Soins intensifs',
                'workplace_id' => $this->workplace->id,
                'bonus_weeks' => 2,
                'bonus_pts' => 4,
                'malus_weeks' => 3,
                'malus_pts' => 8,
                'monday_am' => 2,
                'monday_pm' => 2,
                'tuesday_am' => 2,
                'tuesday_pm' => 2,
                'wednesday_am' => 2,
                'wednesday_pm' => 2,
                'thursday_am' => 2,
                'thursday_pm' => 2,
                'friday_am' => 2,
                'friday_pm' => 2
            ]);

        $response->assertRedirect('/departments');
        $this->assertNotNull(Department::where('name', 'Soins intensifs')->get());
        $this->assertCount(1, Department::all());
    }

    public function test_auth_user_can_see_department_edit_form()
    {
        $department = Department::factory()->create([
            'name' => 'Soins intensifs'
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/departments/{$department->id}/edit");

        $response->assertStatus(200);
    }

    public function test_auth_user_can_edit_department()
    {
        $department = Department::factory()->create([
            'name' => 'Soins intensifs'
        ]);

        $response = $this->actingAs($this->superUser)
            ->put("/departments/{$department->id}", [
                'id' => $department->id,
                'name' => 'Soins intensifs médicaux',
                'workplace_id' => $this->workplace->id,
                'bonus_weeks' => 2,
                'bonus_pts' => 4,
                'malus_weeks' => 3,
                'malus_pts' => 8,
                'monday_am' => 2,
                'monday_pm' => 2,
                'tuesday_am' => 2,
                'tuesday_pm' => 2,
                'wednesday_am' => 2,
                'wednesday_pm' => 2,
                'thursday_am' => 2,
                'thursday_pm' => 2,
                'friday_am' => 2,
                'friday_pm' => 2
            ]);

        $response->assertRedirect("/departments");
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/departments');

        $response->assertRedirect('/login');
    }
}

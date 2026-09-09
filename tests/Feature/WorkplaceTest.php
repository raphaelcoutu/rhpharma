<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\DepartmentType;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WorkplaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_auth_user_can_see_workplaces()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/workplaces');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('workplaces/index', false)
            ->has('workplaces'));
    }

    public function test_auth_user_can_see_workplace()
    {
        $workplace = Workplace::factory()->create([
            'name' => 'CHUS HF',
            'address' => '12e Ave Nord',
            'city' => 'Sherbrooke',
        ]);
        $departmentType = DepartmentType::create(['name' => 'Clinique']);
        Department::factory()->create([
            'name' => 'Urgence',
            'description' => 'Secteur d\'urgence',
            'branch_id' => $this->branch->id,
            'workplace_id' => $workplace->id,
            'department_type_id' => $departmentType->id,
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/workplaces/{$workplace->id}");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('workplaces/show', false)
            ->where('workplace.name', $workplace->name)
            ->has('workplace.departments', 1)
            ->where('workplace.departments.0.description', 'Secteur d\'urgence')
            ->where('workplace.departments.0.department_type.name', 'Clinique'));
    }

    public function test_auth_user_can_see_workplace_create_form()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/workplaces/create');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('workplaces/create', false));
    }

    public function test_auth_user_can_create_workplace()
    {
        $response = $this->actingAs($this->superUser)
            ->post('/workplaces', [
                'name' => 'CHUS HD',
                'code' => 'HD',
                'address' => '123 rue de l\'Hôpital',
                'city' => 'Sherbrooke',
                'province' => 'QC',
                'country' => 'Canada',
                'postal_code' => 'J1J 1J1',
            ]);

        $response->assertRedirect('/workplaces');
        $this->assertDatabaseHas('workplaces', [
            'name' => 'CHUS HD',
            'code' => 'HD',
        ]);
    }

    public function test_auth_user_can_see_workplace_edit_form(): void
    {
        $workplace = Workplace::factory()->create([
            'name' => 'CHUS HF',
        ]);

        $response = $this->actingAs($this->superUser)
            ->get(route('workplaces.edit', $workplace));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('workplaces/edit', false)
            ->where('workplace.name', 'CHUS HF'));
    }

    public function test_auth_user_can_edit_workplace(): void
    {
        $workplace = Workplace::factory()->create([
            'name' => 'CHUS HF',
            'code' => 'HF',
        ]);

        $response = $this->actingAs($this->superUser)
            ->put(route('workplaces.update', $workplace), [
                'name' => 'CHUS HD',
                'code' => 'HD',
                'address' => '123 rue de l\'Hôpital',
                'city' => 'Sherbrooke',
                'province' => 'QC',
                'country' => 'Canada',
                'postal_code' => 'J1J 1J1',
            ]);

        $response->assertRedirectToRoute('workplaces.index');
        $this->assertDatabaseHas('workplaces', [
            'id' => $workplace->id,
            'name' => 'CHUS HD',
            'code' => 'HD',
            'city' => 'Sherbrooke',
        ]);
    }

    public function test_workplace_name_must_be_unique_when_updating_workplace(): void
    {
        $existingWorkplace = Workplace::factory()->create(['name' => 'CHUS HD']);
        $workplace = Workplace::factory()->create(['name' => 'CHUS HF']);

        $response = $this->actingAs($this->superUser)
            ->put(route('workplaces.update', $workplace), [
                'name' => $existingWorkplace->name,
                'code' => 'HF',
                'address' => '123 rue de l\'Hôpital',
                'city' => 'Sherbrooke',
                'province' => 'QC',
                'country' => 'Canada',
                'postal_code' => 'J1J 1J1',
            ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('workplaces', [
            'id' => $workplace->id,
            'name' => 'CHUS HF',
        ]);
    }

    public function test_workplace_code_must_be_unique_when_updating_workplace(): void
    {
        $existingWorkplace = Workplace::factory()->create(['code' => 'HD']);
        $workplace = Workplace::factory()->create(['code' => 'HF']);

        $response = $this->actingAs($this->superUser)
            ->put(route('workplaces.update', $workplace), [
                'name' => 'CHUS HF',
                'code' => $existingWorkplace->code,
                'address' => '123 rue de l\'Hôpital',
                'city' => 'Sherbrooke',
                'province' => 'QC',
                'country' => 'Canada',
                'postal_code' => 'J1J 1J1',
            ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertDatabaseHas('workplaces', [
            'id' => $workplace->id,
            'code' => 'HF',
        ]);
    }

    public function test_user_without_workplace_write_permission_cannot_update_workplace(): void
    {
        $workplace = Workplace::factory()->create(['name' => 'CHUS HF']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('workplaces.update', $workplace), [
                'name' => 'CHUS HD',
                'code' => 'HD',
                'address' => '123 rue de l\'Hôpital',
                'city' => 'Sherbrooke',
                'province' => 'QC',
                'country' => 'Canada',
                'postal_code' => 'J1J 1J1',
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('workplaces', [
            'id' => $workplace->id,
            'name' => 'CHUS HF',
        ]);
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/workplaces');

        $response->assertRedirect('/login');
    }
}

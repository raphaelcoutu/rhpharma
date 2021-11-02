<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkplaceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_auth_user_can_see_workplaces()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/workplaces');

        $response->assertStatus(200);
    }

    public function test_auth_user_can_see_workplace()
    {
        $workplace = Workplace::factory()->create([
            'name' => 'CHUS HF',
            'address' => '12e Ave Nord',
            'city' => 'Sherbrooke'
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/workplaces/{$workplace->id}");

        $response->assertStatus(200);
        $response->assertSee("Lieu : {$workplace->name}", false);
    }

    public function test_auth_user_can_see_workplace_create_form()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/workplaces/create');

        $response->assertStatus(200);
        $response->assertSee('Créer un nouveau lieu de travail');
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
                'postal_code' => 'J1J 1J1'
            ]);

        $response->assertRedirect('/workplaces');
    }

    // Routes n'existent pas
    // public function test_auth_user_can_see_workplace_edit_form()
    // public function test_auth_user_can_edit_workplace()

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/workplaces');

        $response->assertRedirect('/login');
    }
}

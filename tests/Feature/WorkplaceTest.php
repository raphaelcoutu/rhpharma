<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Workplace;
use App\Models\Permission;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkplaceTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->seed(PermissionSeeder::class);

        $this->user = User::factory()->create();
        $this->user->permissions()->saveMany(Permission::all());
    }

    public function test_auth_user_can_see_workplaces()
    {
        $response = $this->actingAs($this->user)
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

        $response = $this->actingAs($this->user)
            ->get("/workplaces/{$workplace->id}");

        $response->assertStatus(200);
        $response->assertSee("Lieu : {$workplace->name}", false);
    }

    public function test_auth_user_can_see_workplace_create_form()
    {
        $response = $this->actingAs($this->user)
            ->get('/workplaces/create');

        $response->assertStatus(200);
        $response->assertSee('Créer un nouveau lieu de travail');
    }

    public function test_auth_user_can_create_workplace()
    {
        $response = $this->actingAs($this->user)
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

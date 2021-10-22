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
                // RENDU ICI
            ]);

        $response->assertRedirect('/workplaces');
    }

    public function test_auth_user_can_see_workplace_edit_form()
    {
        // $newUser = User::factory()->create([
        //     'lastname' => 'Exotic',
        //     'firstname' => 'Joe',
        //     'email' => 'joeexotic@rhpharma.com'
        // ]);

        // $response = $this->actingAs($this->user)
        //     ->get("/users/{$newUser->id}/edit");

        // $response->assertStatus(200);
        // $response->assertSee("Modification d'un utilisateur existant : Joe Exotic", false);
    }


    public function test_auth_user_can_edit_workplace()
    {
        // $newUser = User::factory()->create([
        //     'lastname' => 'Exotic',
        //     'firstname' => 'Joe',
        //     'email' => 'joeexotic@rhpharma.com'
        // ]);

        // $response = $this->actingAs($this->user)
        //     ->put("/users/{$newUser->id}", [
        //         'id' => $newUser->id,
        //         'firstname' => 'Johnny',
        //         'lastname' => 'Exotic',
        //         'email' => 'joeexotic@rhpharma.com',
        //         'workdays_per_week' => 3,
        //         'is_active' => 1
        //     ]);

        // $response->assertRedirect("/users/{$newUser->id}");
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/workplaces');

        $response->assertRedirect('/login');
    }
}

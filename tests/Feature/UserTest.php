<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_auth_user_can_see_users()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/users');

        $response->assertStatus(200);
    }

    public function test_auth_user_can_see_user_page()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com'
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/users/{$newUser->id}");

        $response->assertStatus(200);
        $response->assertSee("Visualisation d'un profil", false);
        $response->assertSeeInOrder(['Exotic', 'Joe', 'joeexotic@rhpharma.com']);
    }

    public function test_user_can_see_is_own_profile()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com'
        ]);

        $response = $this->actingAs($newUser)
            ->get("/profile");

        $response->assertStatus(200);
        $response->assertSee("Mon Profil");
        $response->assertSeeInOrder(['Exotic', 'Joe', 'joeexotic@rhpharma.com']);
    }

    public function test_auth_user_can_see_user_create_form()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/users/create');

        $response->assertStatus(200);
        $response->assertSee('Créer un nouveau utilisateur');
    }

    public function test_auth_user_can_create_user()
    {
        $response = $this->actingAs($this->superUser)
            ->post('/users', [
                'firstname' => 'Johnny',
                'lastname' => 'Exotic',
                'email' => 'joeexotic@rhpharma.com',
                'branch_id' => 1,
                'workdays_per_week' => 3,
                'seniority' => '2020-01-01',
                'is_active' => 1,
                'azure_id'=> 1050
            ]);

        $response->assertRedirect('/users');
    }

    public function test_auth_user_can_see_user_edit_form()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com'
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/users/{$newUser->id}/edit");

        $response->assertStatus(200);
        $response->assertSee("Modification d'un utilisateur existant : Joe Exotic", false);
    }


    public function test_auth_user_can_edit_user()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com'
        ]);

        $response = $this->actingAs($this->superUser)
            ->put("/users/{$newUser->id}", [
                'id' => $newUser->id,
                'firstname' => 'Johnny',
                'lastname' => 'Exotic',
                'email' => 'joeexotic@rhpharma.com',
                'workdays_per_week' => 3,
                'is_active' => 1
            ]);

        $response->assertRedirect("/users/{$newUser->id}");
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/users');

        $response->assertRedirect('/login');
    }
}

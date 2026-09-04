<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
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
            'email' => 'joeexotic@rhpharma.com',
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
            'email' => 'joeexotic@rhpharma.com',
        ]);

        $response = $this->actingAs($newUser)
            ->get('/profile');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('profile/edit', false)
            ->where('auth.user.lastname', 'Exotic')
            ->where('auth.user.firstname', 'Joe')
            ->where('auth.user.email', 'joeexotic@rhpharma.com'));
    }

    public function test_auth_user_can_see_user_create_form()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/users/create');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('users/create', false)
            ->has('branches')
            ->has('roles'));
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
                'azure_id' => 1050,
                'roles' => [],
            ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'firstname' => 'Johnny',
            'lastname' => 'Exotic',
            'email' => 'joeexotic@rhpharma.com',
        ]);
    }

    public function test_auth_user_can_see_user_edit_form()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com',
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/users/{$newUser->id}/edit");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('users/edit', false)
            ->where('user.lastname', 'Exotic')
            ->where('user.firstname', 'Joe')
            ->where('user.email', 'joeexotic@rhpharma.com')
            ->has('branches')
            ->has('roles'));
    }

    public function test_auth_user_can_edit_user()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com',
        ]);

        $response = $this->actingAs($this->superUser)
            ->put("/users/{$newUser->id}", [
                'id' => $newUser->id,
                'firstname' => 'Johnny',
                'lastname' => 'Exotic',
                'email' => 'joeexotic@rhpharma.com',
                'workdays_per_week' => 3,
                'is_active' => 1,
                'roles' => [],
            ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'id' => $newUser->id,
            'firstname' => 'Johnny',
        ]);
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/users');

        $response->assertRedirect('/login');
    }
}

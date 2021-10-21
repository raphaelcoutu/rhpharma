<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Permission;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
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

    public function test_auth_user_can_see_users()
    {
        $this->user = User::factory()->create();
        $this->user->permissions()->saveMany(Permission::all());

        $response = $this->actingAs($this->user)
            ->get('/users');

        $response->assertStatus(200);
    }

    public function test_auth_user_can_see_user_profile()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com'
        ]);

        $response = $this->actingAs($this->user)
            ->get("/users/{$newUser->id}");

        $response->assertStatus(200);
        $response->assertSee("Visualisation d'un profil", false);
        $response->assertSeeInOrder(['Exotic', 'Joe', 'joeexotic@rhpharma.com']);
    }

    public function test_auth_user_can_see_user_edit_page()
    {
        $newUser = User::factory()->create([
            'lastname' => 'Exotic',
            'firstname' => 'Joe',
            'email' => 'joeexotic@rhpharma.com'
        ]);

        $response = $this->actingAs($this->user)
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

        $this->withoutExceptionHandling();

        $response = $this->actingAs($this->user)
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

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Branch;
use App\Models\Permission;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $phmAdmin;
    protected $phmUser;
    protected $atpAdmin;
    protected $atpUser;


    public function setUp() : void
    {
        parent::setUp();

        Branch::create(['name' => 'Pharmaciens']);
        Branch::create(['name' => 'Assistants techniques']);

        foreach(PermissionSeeder::$permissions as $perm) {
            Permission::create($perm);
        }

        $gestionnaire = Role::create(['name' => 'Gestionnaire', 'description' => 'Gestionnaire']);
        $gestionnaire->permissions()->saveMany(Permission::all());

        $this->phmAdmin = User::factory()->create(['branch_id' => 1]);
        $this->phmAdmin->roles()->attach($gestionnaire);
        $this->phmUser = User::factory()->create(['branch_id' => 1]);

        $this->atpAdmin = User::factory()->create(['branch_id' => 2]);
        $this->atpAdmin->roles()->attach($gestionnaire);
        $this->atpUser = User::factory()->create(['branch_id' => 2]);
    }

    public function baseTest($url)
    {
        //PHM-Admin
        $this->actingAs($this->phmAdmin)
            ->get($url)
            ->assertStatus(200);

        //PHM-User
        $this->actingAs($this->phmUser)
            ->get($url)
            ->assertStatus(403);

        //ATP-Admin
        $this->actingAs($this->atpAdmin)
            ->get($url)
            ->assertStatus(200);

        //ATP-Admin
        $this->actingAs($this->atpUser)
            ->get($url)
            ->assertStatus(403);
    }

    /** @test */
    public function validating_branches_permissions()
    {
        $this->baseTest('/branches');
    }

    /** @test */
    public function validating_users_permissions()
    {
        $this->baseTest('/users');
    }
}

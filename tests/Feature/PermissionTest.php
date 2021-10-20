<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $phmAdmin;
    protected $phmUser;
    protected $atpAdmin;
    protected $atpUser;


    public function setUp()
    {
        parent::setUp();

        Branch::create(['name' => 'Pharmaciens']);
        Branch::create(['name' => 'Assistants techniques']);

        foreach(\PermissionSeeder::$permissions as $perm) {
            Permission::create($perm);
        }

        $gestionnaire = Role::create(['name' => 'Gestionnaire', 'description' => 'Gestionnaire']);
        $gestionnaire->permissions()->saveMany(Permission::all());

        $this->phmAdmin = factory(User::class)->create(['branch_id' => 1]);
        $this->phmAdmin->roles()->attach($gestionnaire);
        $this->phmUser = factory(User::class)->create(['branch_id' => 1]);

        $this->atpAdmin = factory(User::class)->create(['branch_id' => 2]);
        $this->atpAdmin->roles()->attach($gestionnaire);
        $this->atpUser = factory(User::class)->create(['branch_id' => 2]);
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

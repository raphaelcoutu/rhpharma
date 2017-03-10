<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gestionnaire = Role::create(['name' => 'Gestionnaire', 'description' => 'Gestionnaire']);
        $utilisateur = Role::create(['name' => 'Utilisateur', 'description' => '']);

        $allPermissions = Permission::all();

        $gestionnaire->permissions()->saveMany($allPermissions);


        $gestionnaire->users()->save(User::find(1));
        $gestionnaire->users()->save(User::find(3));
    }
}

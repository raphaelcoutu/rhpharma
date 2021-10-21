<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
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
        $superuser = Role::create(['name' => 'Super User', 'description' => 'Administration']);
        $gestionnaire = Role::create(['name' => 'Gestionnaire', 'description' => 'Gestionnaire']);
        $utilisateur = Role::create(['name' => 'Utilisateur', 'description' => '']);

        $allPermissions = Permission::all();

        $superuser->permissions()->saveMany($allPermissions);

        $gestionnaire->permissions()->sync([3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);


        $superuser->users()->save(User::find(1));
    }
}

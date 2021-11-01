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
        $utilisateur = Role::create(['name' => 'Utilisateur', 'description' => '']);

        $allPermissions = Permission::all();

        $superuser->permissions()->saveMany($allPermissions);

        $superuser->users()->save(User::find(1));
    }
}

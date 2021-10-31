<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();

        $this->seed(PermissionSeeder::class);

        $this->adminUser = User::factory()->create();
        $this->adminUser->permissions()->saveMany(Permission::all());
    }

    // Delete permission_user
    // Modifier permissions:
        // - Supprimer la column id
        // - Renommer la column name => code
        // - Ajouter une column description
    // Modifier permission_role (permission_id => permission_code)
    // Modifier toutes les policies pour utiliser PermissionEnum
    // Renommer $permissionReadId et permissionWriteId => $permissionReadCode et permissionWriteCode
    // Ajouter role_id (one-to-many) sur Users (set à Default user par défaut)
    // Modifier le form pour users pour modifier le rôle
    // Modifier ValidateReadWritePermissions->validate
        // - Supprimer la section "foreach roles" (maintenant 1 role -> many users)
        // - Supprimer la section "foreach permissions" (car maintenant on ne se fie qu'aux permission du rôle...moins compliqué)


    public function test_unauth_user_cannot_see_roles()
    {
        $response = $this->get('/roles');

        $response->assertRedirect('/login');
    }
}

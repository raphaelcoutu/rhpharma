<?php

namespace Tests;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $superUser;

    protected function createSuperUser(): void
    {
        $this->seed(PermissionSeeder::class);

        $this->superUser = User::factory()->create();
        $superUserRole = Role::create(['name' => 'SuperU', 'description' => '']);
        $this->superUser->roles()->sync($superUserRole);

        $permissions = Permission::all()->pluck('code');
        $superUserRole->permissions()->sync($permissions);
    }

    protected function getAjax($uri, $headers = []): TestResponse
    {
        $ajaxHeader = ['HTTP_X-Requested-With' => 'XMLHttpRequest'];
        return $this->getJson($uri, array_merge($headers, $ajaxHeader));
    }

    protected function postAjax($uri, array $data = [], array $headers = []): TestResponse
    {
        $ajaxHeader = ['HTTP_X-Requested-With' => 'XMLHttpRequest'];
        return $this->postJson($uri, $data, array_merge($headers, $ajaxHeader));
    }

    protected function putAjax($uri, array $data = [], array $headers = []): TestResponse
    {
        $ajaxHeader = ['HTTP_X-Requested-With' => 'XMLHttpRequest'];
        return $this->putJson($uri, $data, array_merge($headers, $ajaxHeader));
    }
}

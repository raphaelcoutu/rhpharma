<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_unauth_user_cannot_see_roles()
    {
        $response = $this->get('/roles');

        $response->assertRedirect('/login');
    }
}

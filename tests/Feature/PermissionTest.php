<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PermissionTest extends TestCase
{

    public function baseTest($url)
    {
        //PHM-Admin
        $this->actingAs(User::find(1))
            ->get($url)
            ->assertStatus(200);

        //PHM-User
        $this->actingAs(User::find(2))
            ->get($url)
            ->assertStatus(403);

        //ATP-Admin
        $this->actingAs(User::find(3))
            ->get($url)
            ->assertStatus(200);

        //ATP-Admin
        $this->actingAs(User::find(4))
            ->get($url)
            ->assertStatus(403);
    }
    
    public function testBranches()
    {
        $this->baseTest('/branches');
    }

    public function testUsers()
    {
        $this->baseTest('/users');
    }
}

<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    public function it_should_return_the_users_initials() {
        $simpleUser = User::factory()->make(['firstname' => 'Raphaël', 'lastname' => 'Coutu']);
        $complexUser = User::factory()->make(['firstname' => 'Émile - Ève', 'lastname' => 'Coutu-Dufresne']);

        $this->assertEquals('RC', $simpleUser->initials);
        $this->assertEquals('ÉÈCD', $complexUser->initials);
    }
}

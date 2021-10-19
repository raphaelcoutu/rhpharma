<?php

namespace Tests\Unit;

use App\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    public function it_should_return_the_users_initials() {
        $simpleUser = factory(User::class)->make(['firstname' => 'Raphaël', 'lastname' => 'Coutu']);
        $complexUser = factory(User::class)->make(['firstname' => 'Émile - Ève', 'lastname' => 'Coutu-Dufresne']);

        $this->assertEquals('RC', $simpleUser->initials);
        $this->assertEquals('ÉÈCD', $complexUser->initials);
    }
}

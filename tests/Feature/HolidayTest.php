<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HolidayTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_see_upcoming_holidays_sorted_by_date(): void
    {
        $this->travelTo('2026-01-15');

        Branch::create(['name' => 'Pharmaciens']);
        $this->createSuperUser();
        Holiday::create(['description' => 'Jour passé', 'date' => '2026-01-01']);
        $upcomingHoliday = Holiday::create(['description' => 'Jour de la famille', 'date' => '2026-02-16']);
        Holiday::create(['description' => 'Jour de l’An prochain', 'date' => '2027-01-01']);

        $response = $this->actingAs($this->superUser)->get('/holidays');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('holidays/index', false)
            ->has('holidays', 2)
            ->where('holidays.0.id', $upcomingHoliday->id)
            ->where('holidays.0.description', 'Jour de la famille')
            ->reloadOnly('holidays', fn (Assert $reload) => $reload
                ->has('holidays', 2)
                ->where('holidays.0.id', $upcomingHoliday->id)));
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/holidays');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_a_holiday_through_the_web_form(): void
    {
        Branch::create(['name' => 'Pharmaciens']);
        $user = User::factory()->create([
            'email' => 'holiday-user@example.test',
        ]);

        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->withCookie(
            config('session.cookie'),
            $loginResponse->getCookie(config('session.cookie'))->getValue()
        )->post('/holidays', [
            'description' => 'Jour de test',
            'date' => '2099-12-25',
        ]);

        $response->assertRedirect('/holidays');
        $holiday = Holiday::where('description', 'Jour de test')->first();

        $this->assertNotNull($holiday);
        $this->assertSame('2099-12-25', $holiday->date->toDateString());
    }

    public function test_authenticated_user_can_update_a_holiday_through_the_web_form(): void
    {
        Branch::create(['name' => 'Pharmaciens']);
        $user = User::factory()->create();
        $holiday = Holiday::create([
            'description' => 'Ancienne description',
            'date' => '2099-12-25',
        ]);

        $response = $this->actingAs($user)->patch('/holidays/'.$holiday->id, [
            'description' => 'Nouvelle description',
            'date' => '2099-12-31',
        ]);

        $response->assertRedirect('/holidays');
        $holiday->refresh();

        $this->assertSame('Nouvelle description', $holiday->description);
        $this->assertSame('2099-12-31', $holiday->date->toDateString());
    }
}

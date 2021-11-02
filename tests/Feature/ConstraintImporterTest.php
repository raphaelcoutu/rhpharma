<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Constraint;
use App\Models\ConstraintType;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Workplace;
use App\Services\AzureRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

class ConstraintImporterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->workplace = Workplace::factory()->create(['name' => 'CHUS']);

        $this->createSuperUser();

        $this->start_date = Carbon::now()->addWeek()->next('Sunday')->format('Y-m-d');
        $this->end_date = Carbon::now()->addWeeks(5)->next('Saturday')->format('Y-m-d');

        $this->schedule = Schedule::factory()->create([
            'name' => 'Prochain horaire',
            'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
            'limit_date' => Carbon::now()->addWeek()->next('Friday'),
            'start_date' => Carbon::parse($this->start_date),
            'end_date' => Carbon::parse($this->end_date)->setTime(23,59,59)
        ]);
    }

    public function test_auth_user_can_see_importer()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/constraintImporter');

        $response->assertStatus(200);
    }

    public function test_import_empty_constraint()
    {
        $this->mock(AzureRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('constraints')->once()->andReturn([]);
        });

        $response = $this->followingRedirects()
            ->actingAs($this->superUser)
            ->get("/constraintImporter/import?start={$this->start_date}&end={$this->end_date}");

        $response->assertStatus(200);
        $response->assertSee('Contraintes importées! (0)');
    }

    public function test_import_constraint_with_existing_user()
    {
        User::factory()->create(['azure_id' => 1000]);
        ConstraintType::factory()->create(['azure_id' => 50]);

        $this->mock(AzureRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('constraints')->once()->andReturn([
                $this->constraint(50, 1000)
            ]);
        });

        $response = $this->followingRedirects()
            ->actingAs($this->superUser)
            ->get("/constraintImporter/import?start={$this->start_date}&end={$this->end_date}");

        $response->assertStatus(200);
        $response->assertSee('Contraintes importées! (1)');
        $this->assertEquals(1, Constraint::all()->count());
    }

    public function test_import_constraint_with_missing_constraint_type()
    {
        User::factory()->create(['azure_id' => 1000]);

        $this->mock(AzureRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('constraints')->once()->andReturn([$this->constraint(50, 1000)]);
            $mock->shouldReceive('constraintTypesByIds')->once()->andReturn([$this->constraintType(50)]);
        });

        $response = $this->followingRedirects()
            ->actingAs($this->superUser)
            ->get("/constraintImporter/import?start={$this->start_date}&end={$this->end_date}");

        $response->assertStatus(200);
        $response->assertSee('ERREUR: Type(s) de contrainte non associée(s)', false);
        $response->assertSeeText('Azure Id: 50');
    }

    public function test_import_constraint_with_missing_user()
    {
        ConstraintType::factory()->create(['azure_id' => 50]);
        ConstraintType::factory()->create(['azure_id' => 51]);

        $this->mock(AzureRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('constraints')->once()->andReturn([
                $this->constraint(50, 1000),
                $this->constraint(51, 1000),
                $this->constraint(50, 1001)
            ]);
        });

        $response = $this
            ->actingAs($this->superUser)
            ->get("/constraintImporter/import?start={$this->start_date}&end={$this->end_date}");

        $response->assertStatus(302);
        $response->assertSessionHas('missingUsers', function($missingUsers) {
            return count($missingUsers) == 2;
        });
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get("/constraintImporter");

        $response->assertRedirect('/login');
    }

    private function constraint($constraintTypeId = null, $userId = null, $firstName = null, $lastName = null)
    {
        $userId = $userId ?? $this->faker->randomNumber(4);
        $firstName = $firstName ?? $this->faker->lastName();
        $lastName = $lastName ?? $this->faker->firstName();
        $constraintTypeId = $constraintTypeId ?? $this->faker->randomNumber(3);

        return [
            'User_id' => $userId,
            'FirstName' => $firstName,
            'LastName' => $lastName,
            'Constraint_id' => $this->faker->randomNumber(4),
            'StartDate' => Carbon::now()->addWeek()->format('Y-m-d H:i:s.u'),
            'EndDate' => Carbon::now()->addWeek()->setTime(23,59,59)->format('Y-m-d H:i:s.u'),
            'Weight' => false,
            'Comment' => '',
            'Status' => 1,
            'NumberOfOccurrences' => null,
            'Disposition' => null,
            'IsDayOfWeek' => null,
            'Day' => null,
            'Day1' => null,
            'Discriminator' => 'Constraint',
            'ConstraintType_id' => $constraintTypeId,
            'Name' => $this->faker->sentence(4)
        ];
    }

    private function constraintType($constraintTypeId = null)
    {
        $constraintTypeId = $constraintTypeId ?? $this->faker->randomNumber(3);

        return [
            'Id' => $constraintTypeId,
            'Name' => $this->faker->sentence(5),
            'Description' => $this->faker->sentence(5)
        ];
    }

    private function user($userId)
    {
        $userId = $userId ?? $this->faker->randomNumber(4);

        return [
            'Id' => $this->faker->randomNumber(5),
            'FirstName' => $this->faker->firstName(),
            'LastName' => $this->faker->lastName()
        ];
    }
}

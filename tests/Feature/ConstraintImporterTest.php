<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Workplace;
use App\Models\Permission;
use App\Services\AzureRepository;
use Illuminate\Support\Carbon;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;

class ConstraintImporterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->workplace = Workplace::factory()->create(['name' => 'CHUS']);

        $this->seed(PermissionSeeder::class);

        $this->user = User::factory()->create();
        $this->user->permissions()->saveMany(Permission::all());

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
        $response = $this->actingAs($this->user)
            ->get('/constraintImporter');

        $response->assertStatus(200);
    }

    public function test_import_empty_constraint()
    {
        $this->mock(AzureRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('constraints')->once()->andReturn([['Constraint']]);
        });

        $this->followingRedirects();
        $response = $this->actingAs($this->user)
            ->get("/constraintImporter/import?start={$this->start_date}&end={$this->end_date}");

        $response->assertStatus(200);
        $response->assertSee('Contraintes importées! (0)');
    }

    public function test_import_unknown_constraint_type()
    {
        User::factory()->create(['azure_id' => 1000]);

        $this->mock(AzureRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('constraints')->once()->andReturn([]);
        });


    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get("/constraintImporter");

        $response->assertRedirect('/login');
    }
}

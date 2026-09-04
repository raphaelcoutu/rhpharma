<?php

namespace Tests\Feature;

use App\Builders\BuildStatus;
use App\Models\AssignedShift;
use App\Models\Branch;
use App\Models\Conflict;
use App\Models\Constraint;
use App\Models\ConstraintType;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use Tests\TestCase;
use ZipArchive;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    private $workplace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);

        $this->createSuperUser();
    }

    public function test_auth_user_can_see_schedules()
    {
        Schedule::factory()->create();

        $response = $this->actingAs($this->superUser)
            ->get('/schedules');

        $response->assertStatus(200);
        $response->assertSee('Horaires');
    }

    public function test_auth_user_can_see_schedules_create_form()
    {
        $response = $this->actingAs($this->superUser)
            ->get('/schedules/create');

        $response->assertStatus(200);
    }

    public function test_auth_user_can_create_schedule()
    {
        $response = $this->actingAs($this->superUser)
            ->post('/schedules', [
                'name' => 'Prochain horaire',
                'branch_id' => $this->branch->id,
                'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
                'limit_date' => Carbon::now()->addWeek()->next('Friday'),
                'start_date' => Carbon::now()->addWeek()->next('Sunday'),
                'end_date' => Carbon::now()->addWeeks(5)->next('Saturday')->setTime(23, 59, 59),
                'branch_id' => 1,
                'status_holidays' => BuildStatus::Standby,
                'status_weekends' => BuildStatus::Standby,
                'status_last_evening' => BuildStatus::Standby,
                'status_clinical_departments' => BuildStatus::Standby,
                'notes' => null,
            ]);

        $response->assertRedirect('/schedules');
        $this->assertNotNull(Schedule::where('name', 'Prochain horaire')->get());
        $this->assertCount(1, Schedule::all());
    }

    public function test_auth_user_can_see_schedule_edit_form()
    {
        $schedule = Schedule::factory()->create([
            'name' => 'Prochain horaire',
            'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
            'limit_date' => Carbon::now()->addWeek()->next('Friday'),
            'start_date' => Carbon::now()->addWeek()->next('Sunday'),
            'end_date' => Carbon::now()->addWeeks(5)->next('Saturday')->setTime(23, 59, 59),
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/schedules/{$schedule->id}/edit");

        $response->assertStatus(200);
    }

    public function test_auth_user_can_edit_schedule()
    {
        $schedule = Schedule::factory()->create([
            'name' => 'Prochain horaire',
            'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
            'limit_date' => Carbon::now()->addWeek()->next('Friday'),
            'start_date' => Carbon::now()->addWeek()->next('Sunday'),
            'end_date' => Carbon::now()->addWeeks(5)->next('Saturday')->setTime(23, 59, 59),
        ]);

        $response = $this->actingAs($this->superUser)
            ->put("/schedules/{$schedule->id}", [
                'id' => $schedule->id,
                'name' => 'Prochain horaire',
                'limit_date_weekends' => Carbon::now()->addWeek()->next('Friday'),
                'limit_date' => Carbon::now()->addWeeks(2)->next('Friday'),
                'start_date' => Carbon::now()->addWeeks(2)->next('Sunday'),
                'end_date' => Carbon::now()->addWeeks(7)->next('Saturday')->setTime(23, 59, 59),
            ]);

        $response->assertRedirect('/schedules');
        $this->assertEquals(Carbon::now()->addWeeks(2)->next('Sunday'), Schedule::findOrFail($schedule->id)->start_date);
    }

    public function test_auth_user_can_export_schedule(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->actingAs($this->superUser)
            ->get("/export/{$schedule->id}");

        $response->assertOk();
        $response->assertDownload();
    }

    public function test_auth_user_can_export_long_schedule_as_three_calendar_workbooks(): void
    {
        $schedule = Schedule::factory()->create([
            'start_date' => Carbon::create(2026, 9, 6),
            'end_date' => Carbon::create(2026, 11, 28),
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/export/{$schedule->id}");

        $response->assertOk();
        $response->assertDownload();

        $archivePath = $response->baseResponse->getFile()->getPathname();
        $archive = new ZipArchive;

        try {
            $this->assertSame(true, $archive->open($archivePath));

            $calendarEntries = [];
            for ($index = 0; $index < $archive->numFiles; $index++) {
                $entryName = $archive->getNameIndex($index);

                if ($entryName !== false && str_starts_with($entryName, 'Horaire_')) {
                    $calendarEntries[] = $entryName;
                }
            }

            $this->assertCount(3, $calendarEntries);
        } finally {
            $archive->close();

            if (is_file($archivePath)) {
                unlink($archivePath);
            }
        }
    }

    public function test_auth_user_can_export_populated_schedule_workbooks(): void
    {
        $schedule = Schedule::factory()->create([
            'start_date' => Carbon::create(2026, 9, 6),
            'end_date' => Carbon::create(2026, 10, 3),
        ]);

        $this->superUser->update([
            'firstname' => 'Super',
            'lastname' => 'Admin',
        ]);

        $workplace = Workplace::factory()->create();
        $department = Department::factory()->create([
            'branch_id' => $this->branch->id,
            'workplace_id' => $workplace->id,
        ]);
        $shiftType = ShiftType::forceCreate([
            'name' => 'Matin',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'branch_id' => $this->branch->id,
        ]);
        $shift = Shift::create([
            'shift_type_id' => $shiftType->id,
            'department_id' => $department->id,
            'code' => 'MAT',
            'description' => 'Quart de matin',
            'is_default' => true,
        ]);
        $user = User::factory()->create([
            'firstname' => 'Jean',
            'lastname' => 'Zzz',
            'branch_id' => $this->branch->id,
        ]);

        AssignedShift::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'is_generated' => true,
            'is_published' => false,
            'date' => $schedule->start_date,
        ]);

        $fixedConstraintType = ConstraintType::factory()->create([
            'name' => 'Absence',
            'code' => 'ABS',
            'status' => 1,
            'is_group_constraint' => false,
            'branch_id' => $this->branch->id,
        ]);
        Constraint::create([
            'user_id' => $user->id,
            'start_datetime' => $schedule->start_date->copy()->addDay()->startOfDay(),
            'end_datetime' => $schedule->start_date->copy()->addDay()->endOfDay(),
            'constraint_type_id' => $fixedConstraintType->id,
            'weight' => true,
            'comment' => 'Absence de Jean',
            'status' => 1,
        ]);

        $groupConstraintType = ConstraintType::factory()->create([
            'name' => 'Disponibilité',
            'code' => 'DISP',
            'status' => 1,
            'is_group_constraint' => true,
            'branch_id' => $this->branch->id,
        ]);
        Constraint::create([
            'user_id' => $user->id,
            'start_datetime' => $schedule->start_date->copy()->startOfDay(),
            'end_datetime' => $schedule->end_date->copy()->endOfDay(),
            'constraint_type_id' => $groupConstraintType->id,
            'weight' => true,
            'comment' => 'Disponibilité de Jean',
            'status' => 1,
        ]);

        $conflict = Conflict::create([
            'schedule_id' => $schedule->id,
            'department_id' => $department->id,
            'severity' => 2,
            'start_date' => $schedule->start_date,
            'end_date' => $schedule->end_date,
            'message' => 'Conflit de test',
        ]);

        $response = $this->actingAs($this->superUser)
            ->get("/export/{$schedule->id}");

        $response->assertOk();
        $response->assertDownload();

        $archivePath = $response->baseResponse->getFile()->getPathname();
        $archive = new ZipArchive;

        try {
            $this->assertSame(true, $archive->open($archivePath));

            $entryNames = [];
            for ($index = 0; $index < $archive->numFiles; $index++) {
                $entryName = $archive->getNameIndex($index);

                if ($entryName !== false) {
                    $entryNames[] = $entryName;
                }
            }

            $this->assertCount(3, $entryNames);

            $calendarEntry = array_values(array_filter(
                $entryNames,
                fn (string $entryName): bool => str_starts_with($entryName, 'Horaire_0_')
                    && str_ends_with($entryName, '.xlsx')
            ))[0] ?? null;
            $liberationEntry = array_values(array_filter(
                $entryNames,
                fn (string $entryName): bool => str_starts_with($entryName, 'Liberations_')
                    && str_ends_with($entryName, '.xlsx')
            ))[0] ?? null;
            $conflictEntry = array_values(array_filter(
                $entryNames,
                fn (string $entryName): bool => str_starts_with($entryName, 'Conflits_')
                    && str_ends_with($entryName, '.xlsx')
            ))[0] ?? null;

            $this->assertNotNull($calendarEntry);
            $this->assertNotNull($liberationEntry);
            $this->assertNotNull($conflictEntry);

            $calendarWorkbook = $this->readWorkbookFromArchive($archive, $calendarEntry);
            $liberationWorkbook = $this->readWorkbookFromArchive($archive, $liberationEntry);
            $conflictWorkbook = $this->readWorkbookFromArchive($archive, $conflictEntry);

            $this->assertSame('Horaire', $calendarWorkbook['title']);
            $this->assertSame('ZZZ, JEAN', $calendarWorkbook['name']);
            $this->assertSame('MAT', $calendarWorkbook['shift']);
            $this->assertInstanceOf(RichText::class, $calendarWorkbook['fixed_constraint']);
            $this->assertSame('ABS', $calendarWorkbook['fixed_constraint']->getPlainText());
            $this->assertSame('Libérations', $liberationWorkbook['title']);
            $this->assertSame('DISP', $liberationWorkbook['constraint']);
            $this->assertSame('Conflits', $conflictWorkbook['title']);
            $this->assertSame($conflict->id, $conflictWorkbook['conflict_id']);
            $this->assertSame('Conflit de test', $conflictWorkbook['message']);
        } finally {
            $archive->close();

            if (is_file($archivePath)) {
                unlink($archivePath);
            }
        }
    }

    /**
     * @return array{title: mixed, name: mixed, shift: mixed, fixed_constraint: mixed, constraint: mixed, conflict_id: mixed, message: mixed}
     */
    private function readWorkbookFromArchive(ZipArchive $archive, string $entryName): array
    {
        $contents = $archive->getFromName($entryName);
        $this->assertIsString($contents);

        $temporaryFile = tempnam(sys_get_temp_dir(), 'export-test-');
        $this->assertIsString($temporaryFile);

        try {
            $this->assertNotFalse(file_put_contents($temporaryFile, $contents));

            $spreadsheet = IOFactory::createReader(IOFactory::READER_XLSX)->load($temporaryFile);
            $sheet = $spreadsheet->getActiveSheet();

            return [
                'title' => $sheet->getTitle(),
                'name' => $sheet->getCell('B4')->getValue(),
                'shift' => $sheet->getCell('C4')->getValue(),
                'fixed_constraint' => $sheet->getCell('D4')->getValue(),
                'constraint' => $sheet->getCell('C4')->getValue(),
                'conflict_id' => $sheet->getCell('A4')->getValue(),
                'message' => $sheet->getCell('E4')->getValue(),
            ];
        } finally {
            if (is_file($temporaryFile)) {
                unlink($temporaryFile);
            }
        }
    }

    public function test_unauth_user_get_redirected()
    {
        $response = $this->get('/schedules');

        $response->assertRedirect('/login');
    }
}

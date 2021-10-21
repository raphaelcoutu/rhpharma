<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Constraint;
use App\Models\ConstraintType;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConstraintTest extends TestCase
{
    use RefreshDatabase;

    private $schedule;

    public function setUp(): void {
        parent::setUp();

        $branch = Branch::create(['name' => 'Pharmaciens']);

        User::factory()->create();

        ConstraintType::create([
            'branch_id' => $branch->id,
            'name' => 'Congé FDS',
            'code' => 'FDS',
            'is_work' => 0,
            'is_single_day' => 1,
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0
         ]);

        $this->schedule = Schedule::create([
            'name' => 'En cours',
            'limit_date' => Carbon::parse('2017-10-01'),
            'limit_date_weekends' => Carbon::parse('2017-10-01'),
            'start_date' => Carbon::parse('2017-11-01'),
            'end_date' => Carbon::parse('2017-11-30'),
            'branch_id' => $branch->id
        ]);
    }

    /** @test */
    public function it_should_retrieve_constraint_overlapping_or_inside_defined_schedule()
    {
        $constraint_single_inside = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-11-15 08:00'),
            'end_datetime' => Carbon::parse('2017-11-15 16:00'),
            ]);
        $constraint_range_inside = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-11-10 08:00'),
            'end_datetime' => Carbon::parse('2017-11-17 16:00'),
        ]);
        $constraint_single_outside = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-10-15 16:00'),
        ]);
        $constraint_range_before_overlapping = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-11-15 16:00'),
        ]);
        $constraint_range_after_overlapping = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-11-15 08:00'),
            'end_datetime' => Carbon::parse('2017-12-15 16:00'),
        ]);
        $constraint_range_before_limit_inside = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-11-01 00:01'),
        ]);
        $constraint_range_after_limit_inside = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-11-30 23:59'),
            'end_datetime' => Carbon::parse('2017-12-15 8:00'),
        ]);
        $constraint_range_before_limit_outside = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-10-31 23:51'),
        ]);
        $constraint_range_after_limit_outside = Constraint::factory()->create([
            'start_datetime' => Carbon::parse('2017-12-01 00:00'),
            'end_datetime' => Carbon::parse('2017-12-15 8:00'),
        ]);

        $constraints_in_schedule = Constraint::inDateInterval($this->schedule->start_date, $this->schedule->end_date)->get();

        $this->assertTrue($constraints_in_schedule->contains($constraint_single_inside));
        $this->assertTrue($constraints_in_schedule->contains($constraint_range_inside));
        $this->assertTrue($constraints_in_schedule->contains($constraint_range_before_overlapping));
        $this->assertTrue($constraints_in_schedule->contains($constraint_range_after_overlapping));
        $this->assertTrue($constraints_in_schedule->contains($constraint_range_before_limit_inside));
        $this->assertTrue($constraints_in_schedule->contains($constraint_range_after_limit_inside));
        $this->assertFalse($constraints_in_schedule->contains($constraint_single_outside));
        $this->assertFalse($constraints_in_schedule->contains($constraint_range_before_limit_outside));
        $this->assertFalse($constraints_in_schedule->contains($constraint_range_after_limit_outside));

        $this->assertCount(6, $constraints_in_schedule);
    }

    /** @test */
    public function it_should_retrieve_unvalidated_constraints()
    {
        $constraint_validated = Constraint::factory()->create([
            'status' => 1,
            'validated_by' => 1
        ]);

        $constraint_unvalidated = Constraint::factory()->create([
            'status' => 0,
            'validated_by' => null
        ]);

        $unvalidated = Constraint::unvalidated()->get();

        $this->assertCount(1, $unvalidated);
        $this->assertTrue($unvalidated->contains($constraint_unvalidated));
    }
}

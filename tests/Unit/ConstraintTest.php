<?php

namespace Tests\Unit;

use App\Constraint;
use App\Schedule;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConstraintTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_retrieve_constraint_overlapping_or_inside_defined_schedule()
    {
        $schedule = Schedule::create([
            'name' => 'En cours',
            'constraint_limit_date' => Carbon::parse('2017-10-01'),
            'start_date' => Carbon::parse('2017-11-01'),
            'end_date' => Carbon::parse('2017-11-30'),
            'branch_id' => 1
        ]);

        $constraint_single_inside = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-11-15 08:00'),
            'end_datetime' => Carbon::parse('2017-11-15 16:00'),
            ]);
        $constraint_range_inside = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-11-10 08:00'),
            'end_datetime' => Carbon::parse('2017-11-17 16:00'),
        ]);
        $constraint_single_outside = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-10-15 16:00'),
        ]);
        $constraint_range_before_overlapping = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-11-15 16:00'),
        ]);
        $constraint_range_after_overlapping = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-11-15 08:00'),
            'end_datetime' => Carbon::parse('2017-12-15 16:00'),
        ]);
        $constraint_range_before_limit_inside = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-11-01 00:01'),
        ]);
        $constraint_range_after_limit_inside = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-11-30 23:59'),
            'end_datetime' => Carbon::parse('2017-12-15 8:00'),
        ]);
        $constraint_range_before_limit_outside = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-10-15 08:00'),
            'end_datetime' => Carbon::parse('2017-10-31 23:51'),
        ]);
        $constraint_range_after_limit_outside = factory(Constraint::class)->create([
            'start_datetime' => Carbon::parse('2017-12-01 00:00'),
            'end_datetime' => Carbon::parse('2017-12-15 8:00'),
        ]);

        $constraints_in_schedule = Constraint::inInterval($schedule->start_date, $schedule->end_date)->get();

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
        $constraint_validated = factory(Constraint::class)->create([
            'validated_by' => 1
        ]);

        $constraint_unvalidated = factory(Constraint::class)->create([
            'validated_by' => null
        ]);

        $unvalidated = Constraint::unvalidated()->get();

        $this->assertCount(1, $unvalidated);
        $this->assertTrue($unvalidated->contains($constraint_unvalidated));
    }
}

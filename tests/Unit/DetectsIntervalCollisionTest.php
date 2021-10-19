<?php

namespace Tests\Unit;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetectsIntervalCollisionTest extends TestCase
{
    /** @test */
    public function it_should_return_false_if_a_is_before_b()
    {
        $a_start = Carbon::parse('2017-10-01');
        $a_end = Carbon::parse('2017-10-31');
        $b_start = Carbon::parse('2017-11-01');
        $b_end = Carbon::parse('2017-11-30');

        $result = detectsIntervalCollision($a_start, $a_end, $b_start, $b_end);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_should_return_false_if_a_is_after_b()
    {
        $a_start = Carbon::parse('2017-12-01');
        $a_end = Carbon::parse('2017-12-31');
        $b_start = Carbon::parse('2017-11-01');
        $b_end = Carbon::parse('2017-11-30');

        $result = detectsIntervalCollision($a_start, $a_end, $b_start, $b_end);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_should_return_true_if_a_intercept_b_from_before()
    {
        $a_start = Carbon::parse('2017-10-01');
        $a_end = Carbon::parse('2017-11-30');
        $b_start = Carbon::parse('2017-11-01');
        $b_end = Carbon::parse('2017-11-30');

        $result = detectsIntervalCollision($a_start, $a_end, $b_start, $b_end);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_return_true_if_a_intercept_b_from_after()
    {
        $a_start = Carbon::parse('2017-11-01');
        $a_end = Carbon::parse('2017-11-30');
        $b_start = Carbon::parse('2017-11-01');
        $b_end = Carbon::parse('2017-12-30');

        $result = detectsIntervalCollision($a_start, $a_end, $b_start, $b_end);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_return_true_if_b_is_inside_a()
    {
        $a_start = Carbon::parse('2017-11-01');
        $a_end = Carbon::parse('2017-11-30');
        $b_start = Carbon::parse('2017-11-02');
        $b_end = Carbon::parse('2017-11-29');

        $result = detectsIntervalCollision($a_start, $a_end, $b_start, $b_end);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_return_true_if_a_is_inside_b()
    {
        $a_start = Carbon::parse('2017-11-01');
        $a_end = Carbon::parse('2017-11-30');
        $b_start = Carbon::parse('2017-10-02');
        $b_end = Carbon::parse('2017-12-15');

        $result = detectsIntervalCollision($a_start, $a_end, $b_start, $b_end);

        $this->assertTrue($result);
    }
}

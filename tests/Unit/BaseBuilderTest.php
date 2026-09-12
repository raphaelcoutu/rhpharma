<?php

namespace Tests\Unit;

use App\Builders\BaseBuilder;
use App\Builders\Precalculation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class BaseBuilderTest extends TestCase
{
    public function test_sampling_is_bounded_for_large_schedules(): void
    {
        $precalculation = (new ReflectionClass(Precalculation::class))->newInstanceWithoutConstructor();
        $builder = new TestableBaseBuilder($precalculation, 11);

        $combinations = $builder->sample([1, 2, 3, 4], 8);

        $this->assertCount(10_000, $combinations);
        $this->assertCount(10_000, array_unique(array_column($combinations, 'sequence')));
    }

    public function test_sampling_keeps_all_combinations_for_small_schedules(): void
    {
        $precalculation = (new ReflectionClass(Precalculation::class))->newInstanceWithoutConstructor();
        $builder = new TestableBaseBuilder($precalculation, 11);

        $combinations = $builder->sample([1, 2], 3);

        $this->assertCount(8, $combinations);
        $this->assertSame(['sequence' => '1,1,1'], $combinations[0]);
        $this->assertSame(['sequence' => '2,2,2'], $combinations[7]);
    }
}

class TestableBaseBuilder extends BaseBuilder
{
    public function sample(array $ids, int $weeksCount): array
    {
        return $this->optimizedSampling($ids, $weeksCount);
    }
}

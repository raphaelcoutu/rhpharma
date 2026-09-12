<?php

namespace Tests\Unit;

use App\Builders\GenericBuilder;
use App\Builders\Precalculation;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class GenericBuilderTest extends TestCase
{
    public function test_active_boolean_pivot_values_are_included_in_department_pharmacist_ids(): void
    {
        $department = new Department;
        $department->id = 11;
        $department->setRelation('pivot', new Pivot(['active' => true]));

        $user = new User([
            'workdays_per_week' => 5,
            'is_manual' => false,
        ]);
        $user->id = 123;
        $user->setRelation('departments', new Collection([$department]));

        $precalculation = (new ReflectionClass(Precalculation::class))->newInstanceWithoutConstructor();
        $precalculation->pharmaciens = new Collection([$user]);

        $builder = (new ReflectionClass(GenericBuilder::class))->newInstanceWithoutConstructor();
        $precalculationProperty = (new ReflectionClass(GenericBuilder::class))->getProperty('precalculation');
        $precalculationProperty->setValue($builder, $precalculation);

        $method = (new ReflectionClass(GenericBuilder::class))->getMethod('pharmacistsIdsInDepartment');

        $this->assertSame([123], $method->invoke($builder, 11));
    }
}

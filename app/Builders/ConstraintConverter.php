<?php

namespace App\Builders;


use App\Constraint;

class ConstraintConverter
{
    private $constraint;
    private $actual;

    private function __construct(Constraint $constraint, $actual)
    {
        $this->constraint = $constraint;
        $this->actual = $actual;
    }

    public static function make($constraint, $actual)
    {
        return (new self($constraint, $actual))->convert();
    }

    private function convert()
    {
        if($this->constraint->constraint_type_id == 2) {
            return ['AM' => 2, 'PM' => $this->actual['PM']];
        }
    }
}
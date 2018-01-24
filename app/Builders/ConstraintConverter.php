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
        $jourConstraints = collect([1,4,5,6,9,10,13,19,22,23,26]);
        $amConstraints = collect([2,7,11,14,17,20,24,27]);
        $pmConstraints = collect([3,8,12,15,18,21,25,28]);

        $constraintId = $this->constraint->constraint_type_id;

        // Indisponibilité JOUR
        if($jourConstraints->contains($constraintId)) {
            return ['AM' => 2, 'PM' => 2];
        }

        // Indisponibilité AM
        else if($amConstraints->contains($constraintId)) {
            return ['AM' => 2, 'PM' => $this->actual['PM']];
        }

        // Indisponibilité PM
        else if($pmConstraints->contains($constraintId)) {
            return ['AM' => $this->actual['AM'], 'PM' => 2];
        }
    }
}
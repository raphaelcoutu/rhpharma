<?php

namespace App\Builders;

abstract class BaseBuilder
{
    protected $weeksPerGroup = 4;
    protected $precalculation;
    protected $departmentId;

    protected $combinaisons;
    protected $scores;


    public function __construct(Precalculation $precalculation, $departmentId)
    {
        $this->precalculation = $precalculation;
        $this->departmentId = $departmentId;
    }

    public function getCombinaisons()
    {
        return $this->combinaisons;
    }

    protected function optimizedSampling($ids, $weeksCount) {
        $nbGroup = ceil($weeksCount / $this->weeksPerGroup);

        $combinaisons = [];

        for($i = 0; $i < $nbGroup; $i++) {
            $count = ($i != $nbGroup -1) ? $this->weeksPerGroup : $weeksCount - ($i*$this->weeksPerGroup);
            $combinaisons[] = $this->sampling($ids, $count);
        }

        return $combinaisons;
    }

    private function sampling($chars, $size, $combinations = array()) {

        # if it's the first iteration, the first set
        # of combinations is the same as the set of characters
        if (empty($combinations)) {
            $combinations = $chars;
        }

        # we're done if we're at size 1
        if ($size == 1) {
            return $this->formatSampling($combinations);
        }

        # initialise array to put new values in
        $new_combinations = array();

        # loop through existing combinations and character set to create strings
        foreach ($combinations as $combination) {
            foreach ($chars as $char) {
                $new_combinations[] = $combination.','.$char;
            }
        }

        # call same function again for the next iteration
        return $this->sampling($chars, $size - 1, $new_combinations);
    }

    private function formatSampling($combinaisons) {
        for($i = 0; $i < count($combinaisons); $i++) {
            $combinaisons[$i] = ['sequence' => $combinaisons[$i]];
        }

        return $combinaisons;
    }
}
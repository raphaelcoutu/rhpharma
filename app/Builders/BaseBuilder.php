<?php

namespace App\Builders;

abstract class BaseBuilder
{
    protected int $maxCombinations = 10_000;

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

    protected function optimizedSampling($ids, $weeksCount): array
    {
        $ids = array_values($ids);

        if ($ids === [] || $weeksCount <= 0) {
            return [];
        }

        $totalCombinations = $this->combinationCount(count($ids), $weeksCount);
        $sampleCount = min($totalCombinations, $this->maxCombinations);
        $lastIndex = $totalCombinations - 1;
        $indexStep = $sampleCount > 1 ? intdiv($lastIndex, $sampleCount - 1) : 0;
        $indexRemainder = $sampleCount > 1 ? $lastIndex % ($sampleCount - 1) : 0;
        $combinations = [];

        for ($sampleIndex = 0; $sampleIndex < $sampleCount; $sampleIndex++) {
            $combinationIndex = $sampleIndex * $indexStep;

            if ($sampleCount > 1) {
                $combinationIndex += intdiv($sampleIndex * $indexRemainder, $sampleCount - 1);
            }

            $combinations[] = [
                'sequence' => $this->sequenceAtIndex($ids, $weeksCount, $combinationIndex),
            ];
        }

        return $combinations;
    }

    private function combinationCount(int $base, int $exponent): int
    {
        $count = 1;

        for ($power = 0; $power < $exponent; $power++) {
            if ($count > intdiv(PHP_INT_MAX, $base)) {
                return PHP_INT_MAX;
            }

            $count *= $base;
        }

        return $count;
    }

    private function sequenceAtIndex(array $ids, int $weeksCount, int $index): string
    {
        $sequence = array_fill(0, $weeksCount, null);
        $base = count($ids);

        for ($week = $weeksCount - 1; $week >= 0; $week--) {
            $sequence[$week] = $ids[$index % $base];
            $index = intdiv($index, $base);
        }

        return implode(',', $sequence);
    }
}

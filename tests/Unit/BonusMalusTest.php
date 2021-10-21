<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BonusMalusTest extends TestCase
{
    protected $combinaisons;

    protected function setUp(): void {

        // 1 = 20, 2 = 16, 3 = 12
        $this->combinaisons = [
            ["sequence" => "1,1,1"],
            ["sequence" => "1,1,2"],
            ["sequence" => "1,1,3"],
            ["sequence" => "1,2,1"],
            ["sequence" => "1,2,2"],
            ["sequence" => "1,2,3"],
            ["sequence" => "1,3,1"],
            ["sequence" => "1,3,2"],
            ["sequence" => "1,3,3"],
        ];

        $scoreTable = [1 => 20, 2 => 16, 3 => 12];

        for($i = 0; $i < count($this->combinaisons); $i++) {
            $sequence = explode(',', $this->combinaisons[$i]['sequence']);
            $this->combinaisons[$i]['score'] = 0;

            foreach($sequence as $seq) {
                $this->combinaisons[$i]['score'] += $scoreTable[$seq];
            }
        }
    }

    protected function applyBonusMalus($bonus, $malus) {
        for($i = 0; $i < count($this->combinaisons); $i++) {
            $sequence = explode(',', $this->combinaisons[$i]['sequence']);

            $lastSeq = 0;
            $consecutiveCount = 1;
            foreach($sequence as $seq) {
                if($seq == $lastSeq) {
                    $consecutiveCount++;

                    if($consecutiveCount >= $bonus['weeks']) {
                        $this->combinaisons[$i]['score'] += $bonus['pts'];
                    }

                    if($consecutiveCount >= $malus['weeks']) {
                        $this->combinaisons[$i]['score'] -= $malus['pts'];
                    }

                } else {
                    //Ici on ce n'est plus le même pharmacien, donc on reset le décompte
                    $lastSeq = $seq;
                    $consecutiveCount = 1;
                }
            }
        }
    }

    /** @test */
    public function sequences_scores_before_bonus_malus() {
        $this->assertEquals(60, $this->combinaisons[0]['score']);
        $this->assertEquals(56, $this->combinaisons[1]['score']);
        $this->assertEquals(52, $this->combinaisons[2]['score']);
        $this->assertEquals(56, $this->combinaisons[3]['score']);
        $this->assertEquals(52, $this->combinaisons[4]['score']);
        $this->assertEquals(48, $this->combinaisons[5]['score']);
        $this->assertEquals(52, $this->combinaisons[6]['score']);
        $this->assertEquals(48, $this->combinaisons[7]['score']);
        $this->assertEquals(44, $this->combinaisons[8]['score']);
    }

    /** @test */
    public function sequences_scores_after_bonus_malus() {
        $this->applyBonusMalus(['weeks' => 2, 'pts' => 10], ['weeks' => 3, 'pts' => 5]);

        $this->assertEquals(75, $this->combinaisons[0]['score']);
        $this->assertEquals(66, $this->combinaisons[1]['score']);
        $this->assertEquals(62, $this->combinaisons[2]['score']);
        $this->assertEquals(56, $this->combinaisons[3]['score']);
        $this->assertEquals(62, $this->combinaisons[4]['score']);
        $this->assertEquals(48, $this->combinaisons[5]['score']);
        $this->assertEquals(52, $this->combinaisons[6]['score']);
        $this->assertEquals(48, $this->combinaisons[7]['score']);
        $this->assertEquals(54, $this->combinaisons[8]['score']);
    }
}

<?php

use App\Triplet;
use Illuminate\Database\Seeder;

class TripletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sans les weekends

        $this->create('**-AAACC-CCBBB-**', false);
        $this->create('*A-AACCC-CBBBB-**', false);
        $this->create('**-AAAAC-CCCBB-B*', false);
        $this->create('*A-AACCC-CCBBB-**', false);
        $this->create('**-AAACC-CCCBB-B*', false);
        $this->create('AA-ACCCC-CBBBB-**', false);
        $this->create('**-AAAAC-CCCCB-BB', false);
        $this->create('*A-AACCC-CCCBB-B*', false);
        $this->create('AA-ACCCC-CCBBB-**', false);
        $this->create('**-AAACC-CCCCB-BB', false);
        $this->create('**-**AAA-CCCBB-B*', false);
        $this->create('**-**AAA-CCCCB-BB', false);
        $this->create('*A-AACCC-BBB**-**', false);
        $this->create('AA-ACCCC-BBB**-**', false);

        // Avec les weekends

        // TODO
    }

    public function create(string $sequence, bool $weekend)
    {
        Triplet::create(['sequence' => $sequence, 'work_weekend' => $weekend]);
    }
}

<?php

use App\ShiftType;
use Illuminate\Database\Seeder;

class ShiftTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShiftType::create([
            'name' => '8',
            'start_time' => '08:00:00',
            'end_time' => '16:30:00',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '9',
            'start_time' => '08:30:00',
            'end_time' => '17:00:00',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '1',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '2',
            'start_time' => '12:30:00',
            'end_time' => '16:30:00',
            'branch_id' => 1
        ]);

    }
}

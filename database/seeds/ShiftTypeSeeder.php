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
            'name' => '08h00-16h30',
            'start_time' => '08:00:00',
            'end_time' => '16:30:00',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '08h30-17h00',
            'start_time' => '08:30',
            'end_time' => '17:00',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '09h00-17h30',
            'start_time' => '09:00',
            'end_time' => '17:30',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '08h00-12h00',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '12h30-16h30',
            'start_time' => '12:30',
            'end_time' => '16:30',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '13h30-22h00',
            'start_time' => '13:30',
            'end_time' => '22:00',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '13h00-17h30',
            'start_time' => '13:00',
            'end_time' => '17:30',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '08h15-16h45',
            'start_time' => '08:15',
            'end_time' => '16:45',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '10h00-18h30',
            'start_time' => '10:00',
            'end_time' => '18:30',
            'branch_id' => 1
        ]);

        ShiftType::create([
            'name' => '00h00-23h59',
            'start_time' => '00:00',
            'end_time' => '23:59',
            'branch_id' => 1
        ]);

    }
}

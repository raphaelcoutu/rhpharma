<?php

use App\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schedule::create([
            'name' => 'Précédent',
            'branch_id' => 1,
            'constraint_limit_date' => Carbon::parse('-4 weeks'),
            'start_date' => Carbon::parse('-3 weeks'),
            'end_date' => Carbon::parse('-1 week - 1 day'),
        ]);

        Schedule::create([
            'name' => 'En cours',
            'branch_id' => 1,
            'constraint_limit_date' => Carbon::parse('-2 weeks'),
            'start_date' => Carbon::parse('-1 week'),
            'end_date' => Carbon::parse('+2 week - 1 day'),
        ]);

        Schedule::create([
            'name' => 'Prochain',
            'branch_id' => 1,
            'constraint_limit_date' => Carbon::parse('+1 week'),
            'start_date' => Carbon::parse('+2 weeks'),
            'end_date' => Carbon::parse('+4 week - 1 day'),
        ]);

    }
}

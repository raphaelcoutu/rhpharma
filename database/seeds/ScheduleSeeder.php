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
        $initial = Carbon::parse('last sunday');

        Schedule::create([
            'name' => 'Précédent (1 semaine)',
            'branch_id' => 1,
            'constraint_limit_date' => $initial->copy()->subWeek(3),
            'start_date' => $initial->copy()->subWeek(2),
            'end_date' => $initial->copy()->subWeek(2)->next(Carbon::SATURDAY),
        ]);

        Schedule::create([
            'name' => 'En cours (4 semaines)',
            'branch_id' => 1,
            'constraint_limit_date' => $initial->copy()->subWeek(2),
            'start_date' => $initial->copy()->subWeek(1),
            'end_date' => $initial->copy()->addWeek(2)->next(Carbon::SATURDAY),
        ]);

        Schedule::create([
            'name' => 'Prochain (6 semaines)',
            'branch_id' => 1,
            'constraint_limit_date' => $initial->copy()->addWeek(2),
            'start_date' => $initial->copy()->addWeek(3),
            'end_date' => $initial->copy()->addWeek(8)->next(Carbon::SATURDAY),
        ]);

        Schedule::create([
            'name' => 'Prochain d\'après (5 semaines)',
            'branch_id' => 1,
            'constraint_limit_date' => $initial->copy()->addWeek(8),
            'start_date' => $initial->copy()->addWeek(9),
            'end_date' => $initial->copy()->addWeek(13)->next(Carbon::SATURDAY),
        ]);
    }
}

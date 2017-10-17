<?php

use App\ConstraintType;
use Illuminate\Database\Seeder;

class ConstraintTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ConstraintType::class)->create(['name' => 'Pas de coumadin', 'code' => 'NoCOU']);
        factory(ConstraintType::class)->create(['name' => 'Single day', 'description' => 'ONE DAY', 'code' => 'DAY', 'is_single_day' => 1]);
        factory(ConstraintType::class)->create(['name' => 'Week', 'description' => 'All week', 'code' => 'week', 'is_single_day' => 0]);
    }
}

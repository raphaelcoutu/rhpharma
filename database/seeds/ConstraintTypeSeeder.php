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
    }
}

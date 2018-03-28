<?php

use App\DepartmentType;
use Illuminate\Database\Seeder;

class DepartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DepartmentType::create(['name' => 'Clinical']);
    }
}

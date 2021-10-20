<?php

namespace Database\Seeders;

use App\Models\DepartmentType;
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
        DepartmentType::create(['name' => 'Clinique']);
        DepartmentType::create(['name' => 'Gestion']);
        DepartmentType::create(['name' => 'Oncologie']);
    }
}

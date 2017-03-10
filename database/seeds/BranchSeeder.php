<?php

use App\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create(['name' => 'Pharmaciens']);
        Branch::create(['name' => 'Assistants techniques']);
    }
}

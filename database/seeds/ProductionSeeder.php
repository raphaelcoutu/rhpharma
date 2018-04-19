<?php

use App\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(DepartmentTypeSeeder::class);
        $this->call(WorkplaceDepartmentSeeder::class);
        $this->call(ConstraintTypeSeeder::class);
        $this->call(ShiftTypeSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(TripletSeeder::class);

        Schedule::create([
            'name' => '2018-05-13 au 2018-06-23',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => Carbon::parse('2018-04-15'),
            'start_date' => Carbon::parse('2018-05-13'),
            'end_date' => Carbon::parse('2018-06-23')
        ]);

        Schedule::create([
            'name' => '2018-06-24 au 2018-09-15',
            'branch_id' => 1,
            'limit_date_weekends' => now(),
            'limit_date' => Carbon::parse('2018-05-20'),
            'start_date' => Carbon::parse('2018-06-24'),
            'end_date' => Carbon::parse('2018-09-15')
        ]);
    }
}

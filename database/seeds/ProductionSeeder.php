<?php

use App\Schedule;
use App\Setting;
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
        $this->call(BranchSeeder::class);
        $this->call(DepartmentTypeSeeder::class);
        $this->call(WorkplaceDepartmentSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ConstraintTypeSeeder::class);
        $this->call(ShiftTypeSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(TripletSeeder::class);

        $departmentsSettings = Setting::where('key', 'departments_order')->get()->first();
        $departmentsSettings->value = '[{"id":4,"active":true,"order":0},{"id":5,"active":true,"order":1},{"id":8,"active":true,"order":2},{"id":6,"active":true,"order":3},{"id":2,"active":true,"order":4},{"id":7,"active":false,"order":5},{"id":9,"active":true,"order":6},{"id":11,"active":true,"order":7},{"id":14,"active":true,"order":8},{"id":15,"active":true,"order":9},{"id":16,"active":true,"order":10},{"id":17,"active":true,"order":11},{"id":10,"active":true,"order":12},{"id":12,"active":true,"order":13}]';
        $departmentsSettings->save();


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

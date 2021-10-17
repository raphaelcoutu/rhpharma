<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
        $this->call(ScheduleSeeder::class);
        $this->call(ConstraintTypeSeeder::class);
        $this->call(ConstraintSeeder::class);
        $this->call(ShiftTypeSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(AssignedShiftSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(TripletSeeder::class);
    }
}

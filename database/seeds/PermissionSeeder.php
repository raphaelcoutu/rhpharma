<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    static $permissions = [
        ['name' => 'ReadBranches'],
        ['name' => 'WriteBranches'],
        ['name' => 'ReadUsers'],
        ['name' => 'WriteUsers'],
        ['name' => 'ReadWorkplaces'],
        ['name' => 'WriteWorkplaces'],
        ['name' => 'ReadDepartments'],
        ['name' => 'WriteDepartments'],
        ['name' => 'ReadRoles'],
        ['name' => 'WriteRoles'],
        ['name' => 'ReadSchedules'],
        ['name' => 'WriteSchedules'],
        ['name' => 'ReadConstraintTypes'],
        ['name' => 'WriteConstraintTypes'],
        ['name' => 'ReadHolidays'],
        ['name' => 'WriteHolidays'],
        ['name' => 'ReadConstraints'],
        ['name' => 'WriteConstraints'],
        ['name' => 'ReadSettings'],
        ['name' => 'WriteSettings'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(self::$permissions as $perm) {
            $p = Permission::create($perm);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    static $permissions = [
        ['code' => 'ReadBranches'],
        ['code' => 'WriteBranches'],
        ['code' => 'ReadUsers'],
        ['code' => 'WriteUsers'],
        ['code' => 'ReadWorkplaces'],
        ['code' => 'WriteWorkplaces'],
        ['code' => 'ReadDepartments'],
        ['code' => 'WriteDepartments'],
        ['code' => 'ReadRoles'],
        ['code' => 'WriteRoles'],
        ['code' => 'ReadSchedules'],
        ['code' => 'WriteSchedules'],
        ['code' => 'ReadConstraintTypes'],
        ['code' => 'WriteConstraintTypes'],
        ['code' => 'ReadHolidays'],
        ['code' => 'WriteHolidays'],
        ['code' => 'ReadConstraints'],
        ['code' => 'WriteConstraints'],
        ['code' => 'ReadSettings'],
        ['code' => 'WriteSettings'],
        ['code' => 'ReadShiftTypes'],
        ['code' => 'WriteShiftTypes'],
        ['code' => 'ReadShifts'],
        ['code' => 'WriteShifts'],
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

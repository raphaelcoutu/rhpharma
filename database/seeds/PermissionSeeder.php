<?php

use App\Permission;
use App\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
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
        ];

        foreach($permissions as $perm) {
            $p = Permission::create($perm);
        }
    }
}

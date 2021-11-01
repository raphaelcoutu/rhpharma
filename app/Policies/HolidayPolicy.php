<?php

namespace App\Policies;

use App\Models\PermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class HolidayPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadCode = PermissionEnum::ReadHolidays;
    private $permissionWriteCode = PermissionEnum::WriteHolidays;
}

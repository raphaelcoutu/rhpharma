<?php

namespace App\Policies;

use App\Models\PermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftTypePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadCode = PermissionEnum::ReadShiftTypes;
    private $permissionWriteCode = PermissionEnum::WriteShiftTypes;
}

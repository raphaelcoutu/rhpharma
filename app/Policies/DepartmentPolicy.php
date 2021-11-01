<?php

namespace App\Policies;

use App\Models\PermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadCode = PermissionEnum::ReadDepartments;
    private $permissionWriteCode = PermissionEnum::WriteDepartments;
}

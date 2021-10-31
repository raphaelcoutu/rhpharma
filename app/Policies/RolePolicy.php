<?php

namespace App\Policies;

use App\Models\PermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = PermissionEnum::ReadRoles;
    private $permissionWriteId = PermissionEnum::WriteRoles;
}

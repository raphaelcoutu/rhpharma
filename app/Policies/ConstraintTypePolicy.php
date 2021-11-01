<?php

namespace App\Policies;

use App\Models\PermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstraintTypePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadCode = PermissionEnum::ReadConstraintTypes;
    private $permissionWriteCode = PermissionEnum::WriteConstraintTypes;
}

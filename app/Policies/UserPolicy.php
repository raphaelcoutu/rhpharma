<?php

namespace App\Policies;

use App\Models\PermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadCode = PermissionEnum::ReadUsers;
    private $permissionWriteCode = PermissionEnum::WriteUsers;
}

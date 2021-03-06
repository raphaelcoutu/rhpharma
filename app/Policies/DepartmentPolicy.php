<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 7;
    private $permissionWriteId = 8;
}

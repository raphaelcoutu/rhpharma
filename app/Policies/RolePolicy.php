<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 9;
    private $permissionWriteId = 10;
}

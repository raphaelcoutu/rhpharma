<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 3;
    private $permissionWriteId = 4;
}

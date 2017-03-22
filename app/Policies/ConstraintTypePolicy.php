<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstraintTypePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 13;
    private $permissionWriteId = 14;
}

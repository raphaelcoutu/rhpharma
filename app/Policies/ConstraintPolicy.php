<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstraintPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 17;
    private $permissionWriteId = 18;
}

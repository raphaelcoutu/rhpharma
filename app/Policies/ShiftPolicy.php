<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 23;
    private $permissionWriteId = 24;
}

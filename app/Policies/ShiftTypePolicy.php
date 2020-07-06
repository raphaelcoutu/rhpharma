<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftTypePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 21;
    private $permissionWriteId = 22;
}

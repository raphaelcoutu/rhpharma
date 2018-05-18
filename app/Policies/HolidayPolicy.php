<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class HolidayPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 15;
    private $permissionWriteId = 16;
}

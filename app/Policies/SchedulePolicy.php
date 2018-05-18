<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 11;
    private $permissionWriteId = 12;
}

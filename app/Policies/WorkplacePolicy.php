<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkplacePolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 5;
    private $permissionWriteId = 6;
}

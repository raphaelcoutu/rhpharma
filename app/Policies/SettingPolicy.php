<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 19;
    private $permissionWriteId = 20;
}

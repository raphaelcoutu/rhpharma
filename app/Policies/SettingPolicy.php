<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 19;
    private $permissionWriteId = 20;
}

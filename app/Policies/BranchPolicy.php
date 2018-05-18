<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 1;
    private $permissionWriteId = 2;

}

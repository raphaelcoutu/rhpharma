<?php

namespace App\Policies;

use App\User;
use App\Branch;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Collection;

class BranchPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadId = 1;
    private $permissionWriteId = 2;

}

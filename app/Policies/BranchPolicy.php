<?php

namespace App\Policies;

use App\Models\PermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization, ValidateReadWritePermissions;

    private $permissionReadCode = PermissionEnum::ReadBranches;
    private $permissionWriteCode = PermissionEnum::WriteBranches;

}

<?php

namespace App\Policies;


use App\User;
use Illuminate\Support\Collection;

trait ValidateReadWritePermissions
{
    public function read(User $user)
    {
        return $this->validate($user, $this->permissionReadId);
    }

    public function write(User $user)
    {
        return $this->validate($user, $this->permissionWriteId);

    }

    private function validate($user, $id) {
        foreach($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                if($permission->id == $id) {
                    return true;
                }
            }
        }

        foreach ($user->permissions as $permission) {
            if($permission->id == $id) {
                return true;
            }
        }

        return false;
    }

}
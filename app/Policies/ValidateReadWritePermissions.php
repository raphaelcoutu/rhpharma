<?php

namespace App\Policies;


use App\Models\User;

trait ValidateReadWritePermissions
{
    public function read(User $user)
    {
        return $this->validate($user, $this->permissionReadCode);
    }

    public function write(User $user)
    {
        return $this->validate($user, $this->permissionWriteCode);

    }

    private function validate($user, $code) {
        foreach($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                if($permission->code == $code) {
                    return true;
                }
            }
        }

        return false;
    }

}

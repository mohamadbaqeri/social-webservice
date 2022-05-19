<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait HasPermissionsTraits
{
    public function getAllPermissions($permission)
    {
        return Permission::whereIn('name', $permission)->get();
    }

    public function hasPermission($permission)
    {
        return $this->permissions->contains('name', $permission);
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
//        foreach ($roles as $role) {
//            if ($this->roles->contains('name', $role)) {
//                return true;
//            }
//        }
    }

}












//    public function hasPermissionTo($permission)
//    {
//        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
//    }
//
//    public function hasPermissionThroughRole($permissions)
//    {
//        foreach ($permissions->roles as $role) {
//            if ($this->roles->contains($role)){
//                return true;
//            }
//        }
//    }

//    public function givePermissionTo(...$permissions)
//    {
//        $permissions = $this->getAllPermissions($permissions);
//        if ($permissions == null){
//            return $this;
//        }
//        $this->permissions()->saveMany($permissions);
//        return $this;
//    }

//    public function permissions()
//    {
//        return $this->belongsToMany(Permission::class, 'user_permission');
//    }
//
//    public function roles()
//    {
//        return $this->belongsToMany(Permission::class, 'role_user');
//    }

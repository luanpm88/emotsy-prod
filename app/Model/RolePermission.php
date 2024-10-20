<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permission';

    public static function createRolePermission($role, $permission)
    {
        $rolePermission = new static();
        $rolePermission->role_id = $role->id;
        $rolePermission->permission = $permission;
        $rolePermission->save();

        return $rolePermission;
    }
}

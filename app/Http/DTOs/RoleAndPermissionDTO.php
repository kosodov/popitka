<?php

namespace App\Http\DTOs;

class RoleAndPermissionDTO
{
    public $role;
    public $permissions;

    public function __construct(RoleDTO $role, array $permissions)
    {
        $this->role = $role;
        $this->permissions = $permissions;
    }
}


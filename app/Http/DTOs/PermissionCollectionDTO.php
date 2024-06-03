<?php

namespace App\Http\DTOs;

class PermissionCollectionDTO
{
    public $permissions;

    public function __construct(array $permissions)
    {
        $this->permissions = $permissions;
    }
}

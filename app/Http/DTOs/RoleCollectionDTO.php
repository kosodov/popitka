<?php

namespace App\Http\DTOs;

class RoleCollectionDTO
{
    public $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }
}
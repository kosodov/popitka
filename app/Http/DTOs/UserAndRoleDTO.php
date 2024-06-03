<?php

namespace App\Http\DTOs;

class UserAndRoleDTO
{
    public $user;
    public $roles;

    public function __construct(UserDTO $user, array $roles)
    {
        $this->user = $user;
        $this->roles = $roles;
    }
}

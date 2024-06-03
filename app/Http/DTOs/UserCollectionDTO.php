<?php

namespace App\Http\DTOs;

class UserCollectionDTO
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}

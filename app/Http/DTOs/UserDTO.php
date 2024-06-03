<?php

namespace App\Http\DTOs;

class UserDTO
{
    public $id;
    public $username;
    public $email;
    public $birthday;
    public $created_at;
    public $updated_at;

    public function __construct($id, $username, $email, $birthday, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->birthday = $birthday;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

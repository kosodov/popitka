<?php

namespace App\Http\DTOs;

class RegisterDTO
{
    public $username;
    public $email;
    public $password;
    public $birthday;

    public function __construct($username, $email, $password, $birthday)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->birthday = $birthday;
    }
}


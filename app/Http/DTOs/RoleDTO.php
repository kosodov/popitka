<?php

namespace App\Http\DTOs;

class RoleDTO
{
    public $name;
    public $description;
    public $code;

    public function __construct($name, $description, $code)
    {
        $this->name = $name;
        $this->description = $description;
        $this->code = $code;
    }
}

<?php

namespace App\Http\DTOs;

class ChangeLogCollectionDTO
{
    public $logs;

    public function __construct(array $logs)
    {
        $this->logs = $logs;
    }
}

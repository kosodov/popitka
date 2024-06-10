<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class LogRequestCollectionDTO
{
    public $logs;

    public function __construct(Collection $logs)
    {
        $this->logs = $logs->map(function ($log) {
            return new LogRequestDTO($log);
        });
    }
}
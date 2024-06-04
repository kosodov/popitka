<?php

namespace App\Http\DTOs;

class ChangeLogDTO
{
    public $entity_type;
    public $entity_id;
    public $before;
    public $after;
    public $created_by;

    public function __construct($entity_type, $entity_id, $before, $after, $created_by)
    {
        $this->entity_type = $entity_type;
        $this->entity_id = $entity_id;
        $this->before = $before;
        $this->after = $after;
        $this->created_by = $created_by;
    }
}

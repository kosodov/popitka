<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'before', 'after', 'created_by'];
    public $timestamps = false;

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];
}

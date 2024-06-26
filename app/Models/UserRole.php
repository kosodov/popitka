<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users_and_roles';

    protected $fillable = [
        'user_id',
        'role_id',
        'created_by',
        'deleted_by',
    ];
}

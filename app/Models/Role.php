<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'code',
        'created_by',
        'deleted_by'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
	
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_and_roles');
    }
}

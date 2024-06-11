<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'code',
        'created_by',
        'deleted_by'
    ];

    protected static function boot()
    {
        parent::boot();

        // Глобальное событие сохранения модели
        static::saving(function ($permission) {
            if (!$permission->created_by) {
                $userId = Auth::id();
                $permission->created_by = $userId;
            }
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}

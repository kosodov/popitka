<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth; // Для доступа к текущему пользователю

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'code',
        'created_by', // Добавляем поле created_by в массив fillable
        'deleted_by'
    ];

    protected static function boot()
    {
        parent::boot();

        // Глобальное событие сохранения модели
        static::saving(function ($permission) {
            // Если поле created_by не заполнено, установим его
            if (!$permission->created_by) {
                // Получаем ID текущего аутентифицированного пользователя (если он есть)
                $userId = Auth::id();
                // Устанавливаем значение created_by на ID текущего пользователя
                $permission->created_by = $userId;
            }
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}

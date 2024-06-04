<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Http\JsonResponse;

class ChangeLogController extends Controller
{
    // Метод для показа изменений, сделанных конкретным пользователем
    public function showUserChangeLogs($id): JsonResponse
    {
        $logs = ChangeLog::where('created_by', $id)->get();
        return response()->json($logs, 200);
    }

    // Метод для показа изменений, связанных с определенным разрешением (permission)
    public function showPermissionChangeLogs($id): JsonResponse
    {
        $logs = ChangeLog::where('entity_type', 'Permission')
                         ->where('entity_id', $id)
                         ->get();
        return response()->json($logs, 200);
    }

    // Метод для показа изменений, связанных с определенной ролью (role)
    public function showRoleChangeLogs($id): JsonResponse
    {
        $logs = ChangeLog::where('entity_type', 'Role')
                         ->where('entity_id', $id)
                         ->get();
        return response()->json($logs, 200);
    }

    // Метод для отката изменений
    public function revert($id): JsonResponse
    {
        $log = ChangeLog::findOrFail($id);
        $modelClass = 'App\Models\\' . $log->entity_type;
        $entity = $modelClass::findOrFail($log->entity_id);

        $entity->update($log->before);

        return response()->json($entity, 200);
    }
}

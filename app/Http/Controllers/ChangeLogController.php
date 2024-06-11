<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChangeLogController extends Controller
{
    public function showUserChangeLogs($id): JsonResponse
    {
        $logs = ChangeLog::where('created_by', $id)->get();
        return response()->json($logs, 200);
    }

    public function showPermissionChangeLogs($id): JsonResponse
    {
        $logs = ChangeLog::where('entity_type', 'Permission')
                         ->where('entity_id', $id)
                         ->get();
        return response()->json($logs, 200);
    }

    public function showRoleChangeLogs($id): JsonResponse
    {
        $logs = ChangeLog::where('entity_type', 'Role')
                         ->where('entity_id', $id)
                         ->get();
        return response()->json($logs, 200);
    }

    public function revert($id): JsonResponse
    {
        $log = ChangeLog::findOrFail($id);
        $modelClass = 'App\Models\\' . $log->entity_type;

        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        $entity = $modelClass::withTrashed()->findOrFail($log->entity_id);

        $before = $log->before;

        if (is_array($before) && !empty($before)) {
            $updateData = $before;
            if (is_array($updateData)) {
                $filteredData = array_diff_key($updateData, array_flip(['id', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'pivot']));

                $debug_info = [
                    'entity_before_update' => $entity->toArray(),
                    'update_data' => $filteredData
                ];

                $update_result = $entity->update($filteredData);

                $entity->refresh();

                $debug_info['entity_after_update'] = $entity->toArray();
                $debug_info['update_result'] = $update_result;

                return response()->json($debug_info, 200);
            } else {
                return response()->json(['error' => 'Invalid update data format'], 400);
            }
        } else {
            return response()->json([
                'error' => 'Invalid data format',
                'data' => $log->before,
                'type' => gettype($before),
                'json_last_error' => json_last_error(),
                'json_last_error_msg' => json_last_error_msg()
            ], 400);
        }
    }

}

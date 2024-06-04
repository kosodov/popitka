<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\ChangeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\DTOs\PermissionDTO;
use App\Http\DTOs\PermissionCollectionDTO;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        $permissions = Permission::all();
        $dto = new PermissionCollectionDTO($permissions->map(function ($permission) {
            return new PermissionDTO($permission->id, $permission->name, $permission->code);
        })->toArray());

        return response()->json($dto, 200);
    }

    public function show($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $dto = new PermissionDTO($permission->id, $permission->name, $permission->code);

        return response()->json($dto, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $permission = Permission::create($request->all());

        // Логируем создание пермишена
        ChangeLog::create([
            'entity_type' => 'Permission',
            'entity_id' => $permission->id,
            'before' => null,
            'after' => $permission->toArray(),
            'created_by' => Auth::user()->id,
        ]);

        $dto = new PermissionDTO($permission->id, $permission->name, $permission->code);

        return response()->json($dto, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $before = $permission->toArray();

        $permission->update($request->all());

        // Логируем обновление пермишена
        ChangeLog::create([
            'entity_type' => 'Permission',
            'entity_id' => $permission->id,
            'before' => $before,
            'after' => $permission->toArray(),
            'created_by' => Auth::user()->id,
        ]);

        $dto = new PermissionDTO($permission->id, $permission->name, $permission->code);

        return response()->json($dto, 200);
    }

    public function destroy($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $before = $permission->toArray();

        $permission->delete();

        // Логируем удаление пермишена
        ChangeLog::create([
            'entity_type' => 'Permission',
            'entity_id' => $permission->id,
            'before' => $before,
            'after' => null,
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(null, 204);
    }

    public function softDelete($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $before = $permission->toArray();

        $permission->delete(); // assuming soft delete is enabled

        // Логируем мягкое удаление пермишена
        ChangeLog::create([
            'entity_type' => 'Permission',
            'entity_id' => $permission->id,
            'before' => $before,
            'after' => null,
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(null, 204);
    }

    public function restore($id): JsonResponse
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $before = $permission->toArray();

        $permission->restore();

        // Логируем восстановление пермишена
        ChangeLog::create([
            'entity_type' => 'Permission',
            'entity_id' => $permission->id,
            'before' => null,
            'after' => $before,
            'created_by' => Auth::user()->id,
        ]);

        $dto = new PermissionDTO($permission->id, $permission->name, $permission->code);

        return response()->json($dto, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\DTOs\PermissionDTO;
use App\Http\DTOs\PermissionCollectionDTO;

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
        $dto = new PermissionDTO($permission->id, $permission->name, $permission->code);

        return response()->json($dto, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $permission->update($request->all());
        $dto = new PermissionDTO($permission->id, $permission->name, $permission->code);

        return response()->json($dto, 200);
    }

    public function destroy($id): JsonResponse
    {
        Permission::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function softDelete($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $permission->delete(); // assuming soft delete is enabled
        return response()->json(null, 204);
    }

    public function restore($id): JsonResponse
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->restore();
        $dto = new PermissionDTO($permission->id, $permission->name, $permission->code);

        return response()->json($dto, 200);
    }
}

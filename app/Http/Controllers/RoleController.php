<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\ChangeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\DTOs\RoleDTO;
use App\Http\DTOs\RoleCollectionDTO;
use App\Http\DTOs\RoleAndPermissionDTO;
use App\Http\DTOs\PermissionDTO;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::all();
        $dto = new RoleCollectionDTO($roles->map(function ($role) {
            return new RoleDTO($role->id, $role->name, $role->description, $role->code);
        })->toArray());

        return response()->json($dto, 200);
    }

    public function show($id): JsonResponse
    {
        $role = Role::with('permissions')->findOrFail($id);
        $dto = new RoleAndPermissionDTO(
            new RoleDTO($role->id, $role->name, $role->description, $role->code),
            $role->permissions->map(function ($permission) {
                return new PermissionDTO($permission->id, $permission->name, $permission->code);
            })->toArray()
        );

        return response()->json($dto, 200);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $role = Role::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'code' => $dto->code,
            'created_by' => Auth::user()->id,
        ]);

        ChangeLog::create([
            'entity_type' => 'Role',
            'entity_id' => $role->id,
            'before' => null,
            'after' => $role->toArray(),
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(new RoleDTO($role->id, $role->name, $role->description, $role->code), 201);
    }

    public function update(UpdateRoleRequest $request, $id): JsonResponse
    {
        $dto = $request->toDTO();
        $role = Role::findOrFail($id);
        $before = $role->toArray();

        $role->update([
            'name' => $dto->name,
            'description' => $dto->description,
            'code' => $dto->code,
            'created_by' => Auth::user()->id,
        ]);

        ChangeLog::create([
            'entity_type' => 'Role',
            'entity_id' => $role->id,
            'before' => $before,
            'after' => $role->toArray(),
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(new RoleDTO($role->id, $role->name, $role->description, $role->code), 200);
    }

    public function destroy($id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $before = $role->toArray();

        $role->delete();

        ChangeLog::create([
            'entity_type' => 'Role',
            'entity_id' => $role->id,
            'before' => $before,
            'after' => null,
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(null, 204);
    }

    public function softDelete($id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $before = $role->toArray();

        $role->delete(); // assuming soft delete is enabled

        ChangeLog::create([
            'entity_type' => 'Role',
            'entity_id' => $role->id,
            'before' => $before,
            'after' => null,
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(null, 204);
    }

    public function restore($id): JsonResponse
    {
        $role = Role::withTrashed()->findOrFail($id);
        $before = $role->toArray();

        $role->restore();

        ChangeLog::create([
            'entity_type' => 'Role',
            'entity_id' => $role->id,
            'before' => null,
            'after' => $before,
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(new RoleDTO($role->id, $role->name, $role->description, $role->code), 200);
    }
}

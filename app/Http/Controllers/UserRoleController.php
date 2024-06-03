<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\DTOs\UserDTO;
use App\Http\DTOs\UserAndRoleDTO;
use App\Http\DTOs\UserCollectionDTO;
use App\Http\DTOs\RoleDTO;

class UserRoleController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('roles')->get();
        $dto = new UserCollectionDTO($users->map(function ($user) {
            return new UserAndRoleDTO(
                new UserDTO($user->id, $user->username, $user->email, $user->birthday, $user->created_at, $user->updated_at),
                $user->roles->map(function ($role) {
                    return new RoleDTO($role->id, $role->name, $role->description, $role->code);
                })->toArray()
            );
        })->toArray());

        return response()->json($dto, 200);
    }

    public function getUserRoles($id): JsonResponse
    {
        $user = User::with('roles')->findOrFail($id);
        $dto = new UserAndRoleDTO(
            new UserDTO($user->id, $user->username, $user->email, $user->birthday, $user->created_at, $user->updated_at),
            $user->roles->map(function ($role) {
                return new RoleDTO($role->id, $role->name, $role->description, $role->code);
            })->toArray()
        );

        return response()->json($dto, 200);
    }

    public function assignRole(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $role = Role::findOrFail($request->role_id);

        $user->roles()->attach($role);

        return response()->json(['message' => 'Role assigned successfully.'], 200);
    }

    public function removeRole($id, $roleId): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->roles()->detach($roleId);

        return response()->json(['message' => 'Role removed successfully.'], 200);
    }

    public function softRemoveRole($id, $roleId): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->roles()->updateExistingPivot($roleId, ['deleted_at' => now()]);

        return response()->json(['message' => 'Role soft removed successfully.'], 200);
    }

    public function restoreRole($id, $roleId): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->roles()->updateExistingPivot($roleId, ['deleted_at' => null]);

        return response()->json(['message' => 'Role restored successfully.'], 200);
    }
}

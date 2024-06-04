<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\ChangeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\DTOs\UserDTO;
use App\Http\DTOs\UserAndRoleDTO;
use App\Http\DTOs\UserCollectionDTO;
use App\Http\DTOs\RoleDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

class UserRoleController extends Controller
{
    protected function logChange($entityType, $entityId, $before, $after)
    {
        ChangeLog::create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'before' => json_encode($before),
            'after' => json_encode($after),
            'created_by' => Auth::user()->id,
        ]);
    }

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
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $role = Role::findOrFail($request->role_id);

            $before = $user->roles->toArray();
            $user->roles()->attach($role);
            $after = $user->roles()->get()->toArray();

            $this->logChange('UserRole', $user->id, $before, $after);

            DB::commit();
            return response()->json(['message' => 'Role assigned successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during role assignment: '.$e->getMessage());
            return response()->json(['error' => 'Role assignment failed'], 500);
        }
    }

    public function removeRole($id, $roleId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $before = $user->roles->toArray();
            $user->roles()->detach($roleId);
            $after = $user->roles()->get()->toArray();

            $this->logChange('UserRole', $user->id, $before, $after);

            DB::commit();
            return response()->json(['message' => 'Role removed successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during role removal: '.$e->getMessage());
            return response()->json(['error' => 'Role removal failed'], 500);
        }
    }

    public function softRemoveRole($id, $roleId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $before = $user->roles->toArray();
            $user->roles()->updateExistingPivot($roleId, ['deleted_at' => now()]);
            $after = $user->roles()->get()->toArray();

            $this->logChange('UserRole', $user->id, $before, $after);

            DB::commit();
            return response()->json(['message' => 'Role soft removed successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during soft role removal: '.$e->getMessage());
            return response()->json(['error' => 'Soft role removal failed'], 500);
        }
    }

    public function restoreRole($id, $roleId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $before = $user->roles->toArray();
            $user->roles()->updateExistingPivot($roleId, ['deleted_at' => null]);
            $after = $user->roles()->get()->toArray();

            $this->logChange('UserRole', $user->id, $before, $after);

            DB::commit();
            return response()->json(['message' => 'Role restored successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during role restoration: '.$e->getMessage());
            return response()->json(['error' => 'Role restoration failed'], 500);
        }
    }
}

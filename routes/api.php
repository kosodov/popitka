<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;



Route::apiResource('permissions', PermissionController::class);
Route::apiResource('roles', RoleController::class);

// User Role Management Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/ref/user', [UserRoleController::class, 'index']);
    Route::get('/ref/user/{id}/role', [UserRoleController::class, 'getUserRoles']);
    Route::post('/ref/user/{id}/role', [UserRoleController::class, 'assignRole']);
    Route::delete('/ref/user/{id}/role/{roleId}', [UserRoleController::class, 'removeRole']);
    Route::delete('/ref/user/{id}/role/{roleId}/soft', [UserRoleController::class, 'softRemoveRole']);
    Route::post('/ref/user/{id}/role/{roleId}/restore', [UserRoleController::class, 'restoreRole']);
});

// Role Management Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/ref/policy/role', [RoleController::class, 'index']);
    Route::get('/ref/policy/role/{id}', [RoleController::class, 'show']);
    Route::post('/ref/policy/role', [RoleController::class, 'store']);
    Route::put('/ref/policy/role/{id}', [RoleController::class, 'update']);
    Route::delete('/ref/policy/role/{id}', [RoleController::class, 'destroy']);
    Route::delete('/ref/policy/role/{id}/soft', [RoleController::class, 'softDelete']);
    Route::post('/ref/policy/role/{id}/restore', [RoleController::class, 'restore']);
});

// Permission Management Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/ref/policy/permission', [PermissionController::class, 'index']);
    Route::get('/ref/policy/permission/{id}', [PermissionController::class, 'show']);
    Route::post('/ref/policy/permission', [PermissionController::class, 'store']);
    Route::put('/ref/policy/permission/{id}', [PermissionController::class, 'update']);
    Route::delete('/ref/policy/permission/{id}', [PermissionController::class, 'destroy']);
    Route::delete('/ref/policy/permission/{id}/soft', [PermissionController::class, 'softDelete']);
    Route::post('/ref/policy/permission/{id}/restore', [PermissionController::class, 'restore']);
});




use App\Http\Controllers\AuthController;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/tokens', [AuthController::class, 'tokens']);
    Route::post('auth/logout_all', [AuthController::class, 'logoutAll']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

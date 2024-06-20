<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json(['user' => $user], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function tokens(Request $request): JsonResponse
    {
        $user = $request->user();
        $tokens = $user->tokens()->get();
        
        $tokens = $tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'tokenable_type' => $token->tokenable_type,
                'tokenable_id' => $token->tokenable_id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at,
                'expires_at' => $token->expires_at,
                'created_at' => $token->created_at,
                'updated_at' => $token->updated_at,
                'token' => $token->token,
            ];
        });

        Log::info('Tokens for user: ', ['tokens' => $tokens]);
        return response()->json(['tokens' => $tokens], 200);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out from all devices successfully'], 200);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        Log::info('Register method called');
        Log::info('Request data: ', $request->all());

        $dto = $request->toDTO();

        try {
            $user = User::create([
                'username' => $dto->username,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'birthday' => $dto->birthday,
            ]);

            Log::info('User created successfully: ', $user->toArray());

            return response()->json(['user' => $user], 201);
        } catch (\Exception $e) {
            Log::error('Error during user registration: '.$e->getMessage());
            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = $request->toDTO();

        $credentials = [
            'username' => $dto->username,
            'password' => $dto->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            Log::info('Token created: ', ['token' => $token]);
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

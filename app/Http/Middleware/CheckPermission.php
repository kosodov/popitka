<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();
        if ($user && $user->hasPermission($permission)) {
            return $next($request);
        }

        return response()->json(['error' => 'Forbidden', 'required_permission' => $permission], 403);
    }
}

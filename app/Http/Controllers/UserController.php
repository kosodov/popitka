<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 


class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $before = $user->toArray();
        
        $user->update($request->all());
        $after = $user->toArray();

        ChangeLog::create([
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'before' => $before,
            'after' => $after,
            'created_by' => Auth::id(),
        ]);

        return response()->json($user, 200);
    }
}

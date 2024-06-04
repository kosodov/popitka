<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Make sure to import the User model

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    $before = $user->toArray();
    
    // Update user with new data from the request
    $user->update($request->all());
    $after = $user->toArray();

    // Log the change
    ChangeLog::create([
        'entity_type' => 'User',
        'entity_id' => $user->id,
        'before' => $before,
        'after' => $after,
        'created_by' => Auth::id(),
    ]);

    return response()->json($user, 200);
}

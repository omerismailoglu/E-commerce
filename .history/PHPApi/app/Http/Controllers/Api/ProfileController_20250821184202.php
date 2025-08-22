<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends ApiController
{
    public function show(Request $request)
    {
        Log::info('Profile viewed', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email
        ]);
        return $this->successResponse($request->user());
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'min:2'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $request->user()->id],
            'password' => ['sometimes', 'string', 'min:8'],
        ]);

        $user = $request->user();
        $oldData = $user->only(['name', 'email']);
        
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
        
        $user->update($validated);
        
        Log::info('Profile updated', [
            'user_id' => $user->id,
            'old_data' => $oldData,
            'new_data' => $user->only(['name', 'email']),
            'password_changed' => isset($validated['password'])
        ]);
        
        return $this->successResponse($user, 'Profil güncellendi');
    }
}



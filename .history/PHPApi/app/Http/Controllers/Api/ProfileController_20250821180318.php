<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    public function show(Request $request)
    {
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
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
        $user->update($validated);
        return $this->successResponse($user, 'Profil güncellendi');
    }
}



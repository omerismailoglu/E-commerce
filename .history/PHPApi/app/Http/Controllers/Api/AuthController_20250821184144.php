<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Cart::firstOrCreate(['user_id' => $user->id]);

        $token = $user->createToken('api')->plainTextToken;

        Log::info('User registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return $this->successResponse([
            'token' => $token,
            'user' => $user,
        ], 'Kayıt başarılı', 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            Log::warning('Login failed: Invalid credentials', [
                'email' => $validated['email'],
                'ip' => $request->ip()
            ]);
            return $this->errorResponse('Kimlik bilgileri hatalı', [], 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        Log::info('User logged in', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return $this->successResponse([
            'token' => $token,
            'user' => $user,
        ], 'Giriş başarılı');
    }

    public function logout(Request $request)
    {
        if ($request->user()?->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
            
            Log::info('User logged out', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'ip' => $request->ip()
            ]);
        }
        return $this->successResponse(null, 'Çıkış yapıldı');
    }
}



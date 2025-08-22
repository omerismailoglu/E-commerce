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
            'role' => 'user', // Explicitly set role
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
            'user' => $user->fresh(), // Ensure fresh data including role
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
        try {
            $user = $request->user();
            if ($user) {
                // Get the bearer token from the request
                $token = $request->bearerToken();
                
                if ($token) {
                    // Find and delete the token by its hash
                    $hashedToken = hash('sha256', $token);
                    $user->tokens()->where('token', $hashedToken)->delete();
                    
                    // Also delete current access token as fallback
                    if ($user->currentAccessToken()) {
                        $user->currentAccessToken()->delete();
                    }
                    
                    // Delete all tokens for complete logout
                    $user->tokens()->delete();
                }
                
                Log::info('User logged out', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);
                
                return $this->successResponse(null, 'Çıkış yapıldı');
            }
            
            return $this->errorResponse('Kullanıcı bulunamadı', [], 401);
        } catch (\Exception $e) {
            Log::error('Logout error', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage()
            ]);
            
            return $this->errorResponse('Çıkış yapılırken hata oluştu', [], 500);
        }
    }
}



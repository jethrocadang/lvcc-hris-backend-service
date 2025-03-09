<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ActivityLog;
use Exception;
use Illuminate\Support\Facades\Log;

class LoginService
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $credentials['email'])->first();
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            ActivityLog::create(['user_id' => $user->id, 'logged_in_at' => now()]);
            
            return response()->json([
                'user' => $user,
                'access_token' => JWTAuth::fromUser($user),
            ]);
        } catch (Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to login'], 500);
        }
    }
}

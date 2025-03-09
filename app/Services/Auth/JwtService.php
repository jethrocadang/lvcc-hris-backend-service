<?php

namespace App\Services\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class JwtService
{
    public function generateTokens(User $user): array
    {
        return [
            'user' => $user,
            'access_token' => JWTAuth::fromUser($user),
            'token_type' => 'Bearer'
        ];
    }

    public function refreshToken(Request $request)
    {
        try {
            $refreshToken = $request->cookie('refresh_token');
            if (!$refreshToken) {
                return response()->json(['error' => 'Refresh token not found'], 401);
            }
            
            $decoded = JWTAuth::setToken($refreshToken)->getPayload();
            if (!$decoded || $decoded['type'] !== 'refresh') {
                return response()->json(['error' => 'Invalid or expired refresh token'], 401);
            }
            
            $user = User::find($decoded['sub']);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            return response()->json($this->generateTokens($user));
            
        } catch (Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to refresh token'], 500);
        }
    }
}

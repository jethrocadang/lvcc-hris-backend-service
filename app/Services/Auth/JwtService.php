<?php

namespace App\Services\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\JsonResponse;

class JwtService
{
    public function generateTokens(User $user): JsonResponse
    {
        // Generate access token with default TTL
        $accessToken = JWTAuth::claims(['type' => 'access'])->fromUser($user);

        // Temporarily set a longer TTL for refresh token
        $refreshTTL = 60 * 24 * 7; // 7 days
        $factory = JWTAuth::factory();
        $defaultTTL = $factory->getTTL(); // Save current TTL
        $factory->setTTL($refreshTTL);

        // Generate refresh token
        $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($user);

        // Reset TTL to default (optional)
        $factory->setTTL($defaultTTL);

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ])->cookie(
            'refresh_token',
            $refreshToken,
            $refreshTTL,   // in minutes
            '/',           // path
            'localhost',          // domain
            false,          // secure
            true,          // httpOnly
            false,         // raw
            'Strict'       // SameSite
        );
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

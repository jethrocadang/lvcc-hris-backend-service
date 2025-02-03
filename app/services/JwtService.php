<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class JwtService
{
    public static function generateJwtToken($user, $type = 'access')
    {
        $ttl = $type === 'access' ? config('jwt.ttl') : config('jwt.refresh_ttl');
        $secret = $type === 'access' ? config('jwt.access_secret') : config('jwt.refresh_secret');

        $customClaims = [
            'user_id' => $user->id,
            'role' => $user->getRoleNames(),
            'type' => $type,
        ];

        // Temporarily override the JWT secret & TTL
        Config::set('jwt.secret', $secret);
        Config::set('jwt.ttl', $ttl);

        // Generate JWT token
        return JWTAuth::claims($customClaims)->fromUser($user);
    }

    public static function decodeJwtToken($token, $type = 'access')
    {
        try {
            $secret = $type === 'access' ? config('jwt.access_secret') : config('jwt.refresh_secret');
            Config::set('jwt.secret', $secret);

            return JWTAuth::setToken(new Token($token))->getPayload();
        } catch (\Exception $e) {
            Log::error('JWT decoding failed: ' . $e->getMessage());
            return null;
        }
    }
}




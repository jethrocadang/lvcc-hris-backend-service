<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JwtService
{
    public static function generateJwtToken($user, $type)
    {
        $key = $type === 'access' ? config('jwt.access_secret') : config('jwt.refresh_secret');
        $payload = [
            'iss' => config('app.url'),
            'aud' => config('app.url'),
            'iat' => time(),
            'exp' => $type === 'access' ? time() + 3600 : time() + (86400 * 7),
            'user_id' => $user->id,
            'role' => $user->id,
            'type' => $type,
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function decodeJwtToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(config('jwt.access_secret'), 'HS256'));
            return $decoded;
        } catch (\Exception $e1) {
            Log::warning('JWT decoding failed with access secret: ' . $e1->getMessage());

            try {
                return JWT::decode($token, new Key(config('jwt.refresh_secret'), 'HS256'));
            } catch (\Exception $e2) {
                Log::error('JWT decoding failed with both secrets: ' . $e2->getMessage());
                return null;
            }
        }
    }
}

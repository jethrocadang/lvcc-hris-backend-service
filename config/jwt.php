<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Secret Key
    |--------------------------------------------------------------------------
    |
    | This key is used to sign the tokens. Make sure to set this in your .env file.
    |
    */
    'refresh_secret' => env('JWT_REFRESH_SECRET', 'fallback_secret_if_env_missing'),
    'access_secret' => env('JWT_ACCESS_SECRET', 'fallback_secret_if_env_missing'),

    /*
    |--------------------------------------------------------------------------
    | JWT Algorithm
    |--------------------------------------------------------------------------
    |
    | The hashing algorithm used for encoding and decoding JWTs.
    | HS256 (HMAC with SHA-256) is commonly used.
    |
    */
    'algo' => 'HS256',

    /*
    |--------------------------------------------------------------------------
    | JWT Token Expiration Times
    |--------------------------------------------------------------------------
    |
    | TTL (Time-To-Live) for access and refresh tokens, in seconds.
    |
    */
    'ttl' => 3600, // Access token expires in 1 hour
    'refresh_ttl' => 86400 * 7, // Refresh token expires in 7 days
];

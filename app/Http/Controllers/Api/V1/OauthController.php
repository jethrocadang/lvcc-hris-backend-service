<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
    // Test endpoint
    public function test()
    {
        return response()->json(['message' => 'working v1']);
    }

    // Handle Google OAuth callback
    public function googleAuthentication(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
            ]);

            // Exchange authorization code for access token
            $response = Http::withHeaders(['Content-Type' => 'application/json', ])
                ->post('https://accounts.google.com/o/oauth2/token', [
                            'client_id' => config('services.google.client_id'),
                            'client_secret' => config('services.google.client_secret'),
                            'redirect_uri' => 'http://localhost:3000',
                            'grant_type' => 'authorization_code',
                            'code' => $request->code,
                        ]);

            if (!$response->successful()) {
                Log::error('Google OAuth Error:', $response->json());
                return response()->json([
                    'error' => 'Failed to exchange authorization code',
                    'code' => $request->code,
                    'google_error' => $response->json(),  // Add detailed Google error message
                ], 400);
            }

            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];

            // Retrieve user data using the access token
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($accessToken);

            // Check if the user exists in the database
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Create a new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar_url' => $googleUser->avatar,
                    'email_verified_at' => now(),
                ]);
            }

            // Generate JWT tokens
            $accessToken = $this->generateJwtToken($user, 'access');
            $refreshToken = $this->generateJwtToken($user, 'refresh');

            // Create an HTTP-only cookie for the refresh token
            $refreshTokenCookie = cookie(
                'refresh_token',
                $refreshToken,
                7 * 24 * 60,
                '/',
                null,
                config('app.env') === 'production',
                true,
                false,
                'lax'
            );

            return response()->json([
                'new' => !$user->wasRecentlyCreated,
                'user' => $user,
                'access_token' => $accessToken,
            ])->withCookie($refreshTokenCookie);
        } catch (\Exception $e) {
            Log::error('Google authentication failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to authenticate', 'message' => $e->getMessage()], 500);
        }
    }

    // Refresh JWT token
    public function refreshToken(Request $request)
    {
        try {
            // Retrieve the refresh token from the HTTP-only cookie
            $refreshToken = $request->cookie('refresh_token');

            if (!$refreshToken) {
                return response()->json(['error' => 'Refresh token not found'], 401);
            }

            // Decode the refresh token
            $decoded = $this->decodeJwtToken($refreshToken);

            if (!$decoded || $decoded->type !== 'refresh') {
                return response()->json(['error' => 'Invalid refresh token'], 401);
            }

            // Find the user
            $user = User::find($decoded->user_id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Generate a new access token
            $accessToken = $this->generateJwtToken($user, 'access');

            return response()->json([
                'access_token' => $accessToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to refresh token', 'message' => $e->getMessage()], 500);
        }
    }

    // Generate JWT token
    private function generateJwtToken($user, $type)
    {
        $key = config('jwt.secret'); // Secret key for signing the token
        $payload = [
            'iss' => config('app.url'), // Issuer
            'aud' => config('app.url'), // Audience
            'iat' => time(), // Issued at
            'exp' => $type === 'access' ? time() + 3600 : time() + 86400 * 7, // Expiration time
            'user_id' => $user->id, // User ID
            'type' => $type, // Token type (access or refresh)
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    // Decode JWT token
    private function decodeJwtToken($token)
    {
        try {
            $key = config('jwt.secret'); // Secret key for verifying the token
            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            Log::error('Token decode failed: ' . $e->getMessage());
            return null;
        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\JwtService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

class OauthController extends Controller
{
    use ApiResponse;

    public function test()
    {
        return $this->successResponse('API is working', ['version' => 'v1']);
    }

    public function googleAuthentication(Request $request)
    {
        try {
            $request->validate(['code' => 'required|string']);

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post('https://accounts.google.com/o/oauth2/token', [
                    'client_id' => config('services.google.client_id'),
                    'client_secret' => config('services.google.client_secret'),
                    'redirect_uri' => config('services.google.frontend_redirect'),
                    'grant_type' => 'authorization_code',
                    'code' => $request->code,
                ]);

            if (!$response->successful()) {
                return $this->errorResponse(
                    'Failed to exchange authorization code',
                    $response->json(),
                    400
                );
            }

            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];

            $googleUser = Socialite::driver('google')->stateless()->userFromToken($accessToken);
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar_url' => $googleUser->avatar,
                    'email_verified_at' => now(),
                ]);
    
                // Log new user creation
                ActivityLog::create([
                    'user_id' => $user->id,
                    'logged_in_at' => now(),
                ]);
                } else {
                    // Log existing user login
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'logged_in_at' => now(),
                    ]);
                }

            if ($user->wasRecentlyCreated) {
                $user->assignRole('user');
            }

            $jwtAccessToken = JWTAuth::fromUser($user);
            $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($user);

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

            return $this->successResponse(
                'Authentication successful',
                [
                    'new_user' => $user->wasRecentlyCreated,
                    'user' => $user,
                    'access_token' => $jwtAccessToken,
                    'token_type' => 'Bearer'
                ]
            )->withCookie($refreshTokenCookie);
        } catch (\Exception $e) {
            return $this->errorResponse('Google authentication failed', [], 500, $e);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $refreshToken = $request->cookie('refresh_token');

            if (!$refreshToken) {
                return $this->errorResponse('Refresh token not found', [], 401);
            }

            $decoded = JwtService::decodeJwtToken($refreshToken);

            if (!$decoded || $decoded->type !== 'refresh' || $decoded->exp < time()) {
                return $this->errorResponse('Invalid or expired refresh token', [], 401);
            }

            $user = User::find($decoded->user_id);

            if (!$user) {
                return $this->errorResponse('User not found', [], 404);
            }

            $newAccessToken = JwtService::generateJwtToken($user, 'access');
            $newRefreshToken = JwtService::generateJwtToken($user, 'refresh');

            $newRefreshTokenCookie = cookie(
                'refresh_token',
                $newRefreshToken,
                7 * 24 * 60,
                '/',
                null,
                config('app.env') === 'production',
                true,
                false,
                'lax'
            );

            return $this->successResponse('Token refreshed successfully', [
                'access_token' => $newAccessToken,
                'token_type' => 'Bearer'
            ])->withCookie($newRefreshTokenCookie);
        } catch (\Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to refresh token', 'message' => $e->getMessage()], 500);
        }

        
    }

    // Log in via email and password
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
                // Find user by email
                $user = User::where('email', $credentials['email'])->first();
        
                if (!$user) {
                    return response()->json(['error' => 'User not found'], 404);
                }
        
                // Verify the password
                if (!Hash::check($credentials['password'], $user->password)) {
                    return response()->json(['error' => 'Invalid credentials'], 401);
                }
    
                //Log user activity
                ActivityLog::create([
                    'user_id' => $user->id,
                    'logged_in_at' => now(),
                ]);
        
                // Generate JWT tokens
                $accessToken = JWTAuth::fromUser($user);
                $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($user);
        
                // Create an HTTP-only cookie for the refresh token
                $refreshTokenCookie = cookie(
                    'refresh_token',
                    $refreshToken,
                    7 * 24 * 60, // 7 days
                    '/',
                    null,
                    config('app.env') === 'production',
                    true,
                    false,
                    'lax'
                );
        
                return response()->json([
                    'user' => $user,
                    'access_token' => $accessToken,
                ])->withCookie($refreshTokenCookie);
            } catch (\Exception $e) {
                Log::error('Login failed: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to login', 'message' => $e->getMessage()], 500);
            }
        }
}

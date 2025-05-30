<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\ActivityLog;
use Exception;
use Illuminate\Support\Str;
use Log;

class GoogleAuthService
{
    /**
     * Authenticates a user using Google OAuth.
     *
     * @param string $code Authorization code from Google.
     * @return User The authenticated user.
     */
    public function authenticate(string $code): User
    {
        try {

            // Exchange and validate autorization code from google if there is really a google user
            $tokenData = $this->exchangeAuthCodeForToken($code);

            // Throw exception if failed
            if (!$tokenData) {
                throw new Exception('Failed to exchange authorization code');
            }

            // If accepted socialite will handle the google Auth
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($tokenData['access_token']);

            // Return user for jwt token!
            return $this->findOrCreateUser($googleUser);
        } catch (Exception $e) {
            Log::error("Failed to authenticate", [$e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Exchanges an authorization code for an access token.
     */
    private function exchangeAuthCodeForToken(string $code): ?array
    {
        // This is for verification for google if there is really a user
        $response = Http::post('https://accounts.google.com/o/oauth2/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.frontend_redirect'),
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        // Return successfull
        return $response->successful() ? $response->json() : null;
    }

    /**
     * Finds or creates a user in the database.
     */
    private function findOrCreateUser($googleUser): User
    {
        // Generate temporary password for credentials login
        $temporaryPassword = Str::random(12);

        // Find or create a new user
        $user = User::firstOrCreate(
            ['google_id' => $googleUser->id],
            [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => $temporaryPassword,
                'google_id' => $googleUser->id,
                'avatar_url' => $googleUser->avatar,
                'email_verified_at' => now()
            ]
        );

        if (!$user->hasAnyRole()) {
            $user->assignRole('employee'); // Replace with your default role name
        }

        // Log the user login for audit trails
        ActivityLog::create(['user_id' => $user->id, 'logged_in_at' => now()]);

        // return the user for the user model
        return $user;
    }
}

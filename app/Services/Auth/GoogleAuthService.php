<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\ActivityLog;

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
        $tokenData = $this->exchangeAuthCodeForToken($code);
        if (!$tokenData) {
            throw new \Exception('Failed to exchange authorization code');
        }

        $googleUser = Socialite::driver('google')->stateless()->userFromToken($tokenData['access_token']);
        return $this->findOrCreateUser($googleUser);
    }

    /**
     * Exchanges an authorization code for an access token.
     */
    private function exchangeAuthCodeForToken(string $code): ?array
    {
        $response = Http::post('https://accounts.google.com/o/oauth2/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.frontend_redirect'),
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Finds or creates a user in the database.
     */
    private function findOrCreateUser($googleUser): User
    {
        $user = User::firstOrCreate(
            ['google_id' => $googleUser->id],
            [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar_url' => $googleUser->avatar,
                'email_verified_at' => now()
            ]
        );
        
        ActivityLog::create(['user_id' => $user->id, 'logged_in_at' => now()]);
        return $user;
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OauthController extends Controller
{
    public function test()
    {
        return response()->json(['message' => 'working v1']);
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleAuthentication(Request $request)
    {
        try {
            // Get user from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if the user exists in the database
            $user = User::where('google_id', $googleUser->id)->first();

            // If the user exists, log them in
            if ($user) {
                Auth::login($user);

                // Generate JWT token
                $token = $user->createToken('YourApp')->accessToken;

                return response()->json([
                    'new' => "false",
                    'user' => $user,
                    'token' => $token,
                ]);
            } else {
                // Create new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar_url' => $googleUser->avatar,
                    'email_verified' => null,
                ]);

                Auth::login($newUser);

                // Generate JWT token for new user
                $token = $newUser->createToken('YourApp')->accessToken;

                return response()->json([
                    'new' => "true",
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar_url' => $googleUser->avatar,
                    'token' => $token,  // Send the JWT token back
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate', 'message' => $e->getMessage()], 500);
        }
    }
}

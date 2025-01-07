<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OauthController extends Controller
{
    public function googleLogin() {
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication(Request $request) {
        try{
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->id)->first();

        if ($user){
            Auth::login($user);

            return response()->json([
                'new' => "false",
                'user' => $user,
            ]);

        }else{
           $newUser =  User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make('Password@123'),
                'google_id' => $googleUser->id,
                'avatar_url' => $googleUser->avatar,
                'email_verified' => null,
            ]);

            if ($newUser){
                Auth::login($newUser);
                return response()->json([
                    'new' => "true",
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make('Password@123'),
                    'google_id' => $googleUser->id,
                    'avatar_url' => $googleUser->avatar,
                ]);
                }
              }
        }catch(Exception){
            dd($e);
        }
        
    }
}


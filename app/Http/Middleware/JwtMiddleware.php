<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        //check if there is a token
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Decode the token
            $decoded = JWT::decode($token, new Key(config('jwt.access_secret'), 'HS256'));

            if ($decoded->type !== 'access') {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Check if there is a user
            $user = User::find($decoded->user_id);

            if (!$user) {
                return response()->json(['error' => 'User not found!', 404]);
            }

            // Attach the user to the request
            $request->merge(['auth_user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid Token', 'message' => $e->getMessage(), 401]);
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            Log::info('JwtMiddleware triggered', ['path' => $request->path()]);

            Log::info('JWT TTL:', [
                'value' => config('jwt.ttl'),
                'type' => gettype(config('jwt.ttl'))
            ]);

            $token = JWTAuth::getToken();
            $payload = JWTAuth::decode($token);
            Log::info('Decoded JWT payload', $payload->toArray());
            Log::info('Using DB:', ['db' => DB::connection()->getDatabaseName()]);
            Log::info('Tenant before auth', ['tenant' => Tenant::current()?->id]);

            // Check if token exists & parse it
            $user = JWTAuth::parseToken()->authenticate();

            Log::info('Auth DB connection:', ['connection' => optional($user)->getConnectionName()]);
            Log::info('User not found', ['User:' => $user]);

            if (!$user) {
                return response()->json(['error' => 'User not found!'], 404);
            }

            // Attach the authenticated user to the request
            $request->merge(['auth_user' => $user]);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is missing'], 401);
        }

        return $next($request);
    }
}

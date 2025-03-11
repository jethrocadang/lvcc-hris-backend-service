<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\User;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        try {
            // Attempt to authenticate user from token
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Load roles from the database if not eager loaded
        $user->loadMissing('roles');

        // Check if the user has any of the required roles
        $roleList = explode('|', $roles);
        if (!$user->hasAnyRole($roleList)) {
            return response()->json(['error' => 'Forbidden - Insufficient permissions'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

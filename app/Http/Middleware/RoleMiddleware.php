<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }

        // Decode JWT Token (Ensure it's valid & not expired)
        $decoded = JwtService::decodeJwtToken($token);

        if (!$decoded || $decoded->type !== 'access' || $decoded->exp < time()) {
            return response()->json(['error' => 'Invalid or expired token'], Response::HTTP_UNAUTHORIZED);
        }

        // Retrieve User & Load Roles
        $user = User::with('roles')->find($decoded->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Check User Role (Supports Multiple Roles)
        $roleList = explode('|', $roles); // Allow multiple roles via pipe (e.g., "employee|evaluator")
        if (!$user->hasAnyRole($roleList)) {
            return response()->json(['error' => 'Forbidden - Insufficient permissions'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
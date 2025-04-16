<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Multitenancy\Models\Tenant;
use App\Models\JobApplication;

class TenantJwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Make sure tenant is already identified
        $tenant = Tenant::current();
        if (! $tenant) {
            return response()->json(['message' => 'Tenant not found.'], 400);
        }

        // Dynamically set the connection on the JobApplication model used by JWT
        // This forces JWTAuth to use the tenant connection when resolving the user
        config(['auth.providers.users.model' => JobApplication::class]);

        // Optional: Set tenant DB connection manually if needed
        (new JobApplication)->setConnection(config('multitenancy.tenant_database_connection_name'));

        return $next($request);
    }
}


<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If it's a preflight request, return the headers immediately
        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', Response::HTTP_OK, $this->corsHeaders());
        }

        // Continue request processing
        $response = $next($request);

        // Set CORS headers
        foreach ($this->corsHeaders() as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }

    private function corsHeaders()
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, DELETE, PUT',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ];
    }
}

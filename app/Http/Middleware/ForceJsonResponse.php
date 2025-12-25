<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force JSON response for API routes
        if ($request->is('api/*') || $request->expectsJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        $response = $next($request);

        // Ensure JSON response for API routes even on errors
        if ($request->is('api/*') || $request->expectsJson()) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}


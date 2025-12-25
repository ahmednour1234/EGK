<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePagination
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('per_page')) {
            $perPage = (int) $request->input('per_page');
            
            // Clamp per_page between 1 and 100
            $perPage = max(1, min(100, $perPage));
            
            $request->merge(['per_page' => $perPage]);
        }

        return $next($request);
    }
}


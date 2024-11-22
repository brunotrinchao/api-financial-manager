<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{

    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->access_level === 'admin') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized.'], Response::HTTP_FORBIDDEN);
    }
}

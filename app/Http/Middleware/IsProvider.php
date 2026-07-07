<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsProvider
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'provider') {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
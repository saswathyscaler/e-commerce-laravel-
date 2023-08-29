<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->email === 'saswatranjan0602@gmail.com') {
            return $next($request);
        }

        return response()->json([
            'message' => 'Unauthorized',
            'status' => 401
        ], 401);
    }
}

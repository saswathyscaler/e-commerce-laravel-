<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->email === 'saswatranjan0602@gmail.com') {
            return $next($request);
        }

        return response()->json([
            'message' => 'Unauthorized',
            'status' => 403
        ], 403);
    }
}

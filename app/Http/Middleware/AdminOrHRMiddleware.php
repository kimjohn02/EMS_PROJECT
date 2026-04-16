<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOrHRMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()?->role, ['admin', 'hr'], true)) {
            abort(403, 'Access denied. Admin or HR only.');
        }
        return $next($request);
    }
}

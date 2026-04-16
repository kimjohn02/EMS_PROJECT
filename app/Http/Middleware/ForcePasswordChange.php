<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->requires_password_change) {
            if (!$request->routeIs('password.force-change', 'password.force-change.store', 'logout')) {
                return redirect()->route('password.force-change')->with('error', 'You must change your default password before continuing.');
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Check if the authenticated user has the given role
        if (Auth::check() && Auth::user()->user_type === $role) {
            return $next($request);
        }

        // If the user does not have the role, return a 403 Forbidden response
        return response()->json(['error' => 'Forbidden'], 403);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->rol !== $role) {
            abort(403, 'No tienes permisos para acceder.');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperadminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isSuperadmin()) {
            return redirect('/')->with('error', 'No tenés permisos para acceder a esta sección.');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InmobiliariaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        $user = auth()->user();
        
        if (!$user->isInmobiliaria()) {
            return redirect('/')->with('error', 'Esta sección es solo para inmobiliarias.');
        }
        
        if (!$user->inmobiliaria || !$user->inmobiliaria->is_approved) {
            return redirect('/')->with('error', 'Tu cuenta de inmobiliaria aún no fue aprobada.');
        }
        
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GestorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->canViewAllRequests()) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Acceso denegado. Se requiere perfil de Gestor o Admin.');
    }
}

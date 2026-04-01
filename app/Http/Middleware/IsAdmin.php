<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Ce middleware bloque l'accès si l'utilisateur n'est pas admin.
     * Il s'utilise sur les routes d'administration.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie que l'utilisateur est connecté ET qu'il est admin
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Accès refusé. Vous devez être administrateur.');
        }

        return $next($request);
    }
}

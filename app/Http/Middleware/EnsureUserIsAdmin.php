<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar que el usuario tenga rol de administrador
        if (auth()->user()->role !== 'admin') {
            // Si no es admin, redirigir al dashboard apropiado según su rol
            if (auth()->user()->role === 'supplier') {
                return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a la sección de administración.');
            } elseif (auth()->user()->role === 'client') {
                return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a la sección de administración.');
            }
            
            // Si no tiene rol definido, mostrar error 403
            abort(403, 'Acceso denegado. Solo los administradores pueden acceder a esta sección.');
        }

        return $next($request);
    }
}

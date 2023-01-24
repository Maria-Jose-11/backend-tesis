<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
        // Función para verificar el rol del usuario
    public function handle(Request $request, Closure $next, string $tipoUsuario_slug)
    {
        // Se obtiene la ruta que gestiona la petición
        $user = $request->route('user');
        // Si el usuario no es el mismo rol
        if (!$user->hasRole($tipoUsuario_slug))
        {
            return abort(403, 'Acción no autorizada.');
        }
        // Si el usuario tiene el mismo rol pasa a realizar las siguientes acciones
        return $next($request);
    }
    
}    

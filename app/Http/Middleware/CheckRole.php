<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Permite pasar uno o más roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Verifica si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['status' => false, 'message' => 'No autenticado.'], 401);
        }

        // Obtiene el usuario autenticado
        $user = Auth::user();

        // Verifica si el usuario tiene alguno de los roles permitidos
        foreach ($roles as $role) {
            if ($user->rol_usuario === $role) {
                return $next($request); // El usuario tiene el rol, permite el acceso
            }
        }

        // Si el usuario no tiene ninguno de los roles permitidos
        return response()->json(['status' => false, 'message' => 'No autorizado. Rol no permitido.'], 403);
    }
}

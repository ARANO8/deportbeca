<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware de autorizacion granular basado en la tabla rol_modulo_permiso.
 *
 * Uso en rutas:
 *   ->middleware('permiso:carreras,ver')
 *   ->middleware('permiso:usuarios,crear')
 *   ->middleware('permiso:fixture,editar')
 *
 * Modulos disponibles:
 *   usuarios · carreras · disciplinas · lugares · preinscripciones
 *   fixture  · calificaciones · eventos · roles · privilegios
 *
 * Permisos disponibles: ver · crear · editar · eliminar
 *
 * El rol 'Administrador' tiene acceso total a todos los modulos.
 * El resto de roles solo pueden acceder segun lo configurado en
 * la seccion Privilegios del panel de administracion.
 */
class CheckPermiso
{
    public function handle(Request $request, Closure $next, string $modulo, string $permiso = 'ver')
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->rol) {
            abort(403, 'Tu cuenta no tiene un rol asignado. Contacta al administrador del sistema.');
        }

        // Los roles marcados como super admin tienen acceso irrestricto.
        // No depende del nombre del rol: renombrarlo no rompe la autorizacion.
        if ($user->rol->es_super_admin) {
            return $next($request);
        }

        // Verificar permiso especifico en rol_modulo_permiso
        if ($user->rol->tienePermiso($modulo, $permiso)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'No tienes permiso para realizar esta accion.'], 403);
        }

        abort(403, 'No tienes permiso para acceder a este modulo.');
    }
}

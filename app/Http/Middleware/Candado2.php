<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Jugadores;
use App\Models\Usuarios;

// class Candado2
//  {
//      /**
//       * Handle an incoming request.
//       *
//       * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//       */
//      public function handle(Request $request, Closure $next): Response
//      {
//         dd('Hola candado 2');
//          return $next($request);
//      }
//  }

class Candado2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  $clave
     * @return mixed
     */
     public function handle($request, Closure $next, $clave)
    // public function handle($request, Closure $next, $clave = null)
    {
        // 1. Recupero el usuario que inició sesión
        $usuario = Auth::user();
        $validacion = DB::table('usuario')
            ->join('rol', 'rol.id', '=', 'usuario.idrol')
            ->join('rolpermiso', 'rol.id', '=', 'rolpermiso.idrol')
            ->join('permiso', 'rolpermiso.idpermiso', '=', 'permiso.id')
            ->select(
                'usuario.email',
                'permiso.nombre',
                'permiso.clave',
                'permiso.id as idpermiso',
                'rol.nombre as rol'
            )


            ->where('usuario.id', $usuario->id)
            ->where('permiso.clave', '=', $clave)
            // ->when($clave, function ($query) use ($clave) {
            //     return $query->where('permisos.clave', '=', $clave);
            // })
            // ->where('usuario.id', 2)
            // ->where('permisos.clave', '=', 'GUSU')
            ->first();

        if ($validacion) {
            // Tiene un permiso en específico
            // Si tiene el permiso ejecuta la línea de abajo
            // dd('Tiene Permiso');
            return $next($request);
        } else {
            // dd('No Tiene Permiso');
            return redirect()->route('sinpermiso');
        }
    }
}
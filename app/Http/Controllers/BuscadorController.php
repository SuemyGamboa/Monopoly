<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuscadorController extends Controller
{
    function index(Request $r){
        $context = $r->all();

        if(isset($context['criterio'])){
            $criterio = $context['criterio'];
            
    //         $consulta = DB::table('tipopropiedad')
    // ->join('propiedad', 'propiedad.idtipopropiedad', '=', 'tipopropiedad.id')
    // ->join('region', 'propiedad.idregion', '=', 'region.id')
    // ->join('propiedadjugador', 'propiedad.id', '=', 'propiedadjugador.idpropiedad')
    // ->join('partida', 'partida.id', '=', 'propiedadjugador.idpartida')
    // ->join('jugador', 'jugador.id', '=', 'propiedadjugador.idjugador')
    // ->where(function ($query) use ($criterio) {
    //     $query->where('tipopropiedad.nombre', 'like', "%{$criterio}%")
    //           ->orWhere('region.nombre', 'like', "%{$criterio}%")
    //           ->orWhere('jugador.nombre', 'like', "%{$criterio}%")
    //           ->orWhere('propiedad.precio', 'like', "%{$criterio}%");
    // })
    // ->select(
    //     'tipopropiedad.nombre as tipopropiedad',
    //     'region.nombre as region',
    //     'propiedad.precio',
    //     'partida.clave',
    //     'jugador.nombre as jugador'
    // );


    $consulta = DB::table('tipopropiedad')
    ->join('propiedad', 'propiedad.idtipopropiedad', '=', 'tipopropiedad.id')
    ->join('region', 'propiedad.idregion', '=', 'region.id')
    ->join('propiedadjugador', 'propiedad.id', '=', 'propiedadjugador.idpropiedad')
    ->join('partida', 'partida.id', '=', 'propiedadjugador.idpartida')
    ->join('jugador', 'jugador.id', '=', 'propiedadjugador.idjugador')
    ->where(function ($query) use ($criterio) {
        // Condición para buscar por clave de partida o nombre de jugador
        $query->where('partida.clave', 'like', "%{$criterio}%")
              ->orWhere('jugador.nombre', 'like', "%{$criterio}%");
    })
    ->select(
        'tipopropiedad.nombre as tipo_propiedad',
        'region.nombre as region',
        'propiedad.precio',
        'partida.clave',
        'jugador.nombre as jugador'
    );
                
            
            // Ejecutar consulta
            $registros = $consulta->get();
        } else {
            $criterio = '';
            $registros = array();
        }

        $datos = array();
        $datos['registro'] = $registros;
        $datos['criterio'] = $criterio;
        return view('buscador.index', compact('registros', 'criterio'));
    }
}

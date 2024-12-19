<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropiedadJugador;

use App\Models\Propiedad;
use App\Models\Jugadores;
use App\Models\Partidas;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\DB;

class PropiedadjugadorController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        //$datos['registros'] = Propiedadxjugador::all();
        $datos['registros'] = DB::table('propiedadjugador')
        ->join('propiedad', 'propiedadjugador.idpropiedad', '=', 'propiedad.id')
        ->join('jugador', 'propiedadjugador.idjugador', '=', 'jugador.id')
        ->join('partida', 'propiedadjugador.idpartida', '=', 'partida.id')
        ->select(
            'propiedadjugador.*',
            'jugador.nombre as jugador_nombre', 
            'partida.clave as partida_clave', // Suponiendo que la columna de nombre en 'partida' es 'clave'
            'propiedad.nombre as propiedad_nombre'
        )
        ->get();
    

        return view('propiedadjugador.listado')->with($datos);
    }

    function formulario($id = 0){
        $datos = array();
        if($id == 0){
            // Agregar
            $datos['propiedadjugador'] = new PropiedadJugador();
            $datos['operacion'] = 'Agregar';
            $datos['propiedadjugador'] -> id = 0;
        } else {
            // Editar
            $datos['propiedadjugador'] = PropiedadJugador::find($id);
            $datos['operacion'] = 'Modificar';
        }
        $datos['propiedad'] = Propiedad::all();
        $datos['jugador'] = Jugadores::all();
        $datos['partida'] = Partidas::all();

        return view('propiedadjugador.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request) {
    $context = $request->all();

    switch ($context['operacion']) {
        case 'Agregar':
            $propiedadjugador = new PropiedadJugador();
            $propiedadjugador->idpropiedad = $context['idpropiedad'];
            $propiedadjugador->idjugador = $context['idjugador'];
            $propiedadjugador->idpartida = $context['idpartida'];
            $propiedadjugador->save();
            break;

        case 'Modificar':
            $propiedadjugador = PropiedadJugador::find($context['id']);
            if ($propiedadjugador) {
                $propiedadjugador->idpropiedad = $context['idpropiedad'];
                $propiedadjugador->idjugador = $context['idjugador'];
                $propiedadjugador->idpartida = $context['idpartida'];
                $propiedadjugador->save();
            } else {
                // Manejar el caso donde el registro no existe
                return redirect()->route('index_propiedadjugador')->with('error', 'Registro no encontrado para modificar.');
            }
            break;

        case 'Eliminar':
            $propiedadjugador = PropiedadJugador::find($context['id']);
            if ($propiedadjugador) {
                $propiedadjugador->delete();
            } else {
                // Manejar el caso donde el registro no existe
                return redirect()->route('index_propiedadjugador')->with('error', 'Registro no encontrado para eliminar.');
            }
            break;
    }
    return redirect()->route('index_propiedadjugador');
}
}
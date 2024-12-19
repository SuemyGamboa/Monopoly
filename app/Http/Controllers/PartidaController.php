<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partidas;
use App\Models\Propiedad;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\BusinessLogic\BoPartida;
use App\BusinessLogic\BoTurno;
use App\BusinessLogic\BoControl;
use Illuminate\Support\Facades\DB;
class PartidaController extends Controller
{
    function index()
    {
        // Listar todos los registros de los alumnos
        $datos = array();
        $datos['registros'] = Partidas::all();
        return view('partida.listado')->with($datos);
    }

    function formulario($id = 0)
    {
        $datos = array();
        if ($id == 0) {
            // Agregar
            $datos['partida'] = new Partidas();
            $datos['operacion'] = 'Agregar';
            $datos['partida']->id = 0;
        } else {
            // Editar
            $datos['partida'] = Partidas::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('partida.formulario')->with($datos);
    }

  
    function save(Request $request)
    {
        $context = $request->all();
        switch ($context['operacion']) {
            case 'Agregar':
                $partida = new Partidas();
                
                $partida->nombre = $context['nombre'];
                $partida->clave = $context['clave'];
                $partida->fecha = $context['fecha'];
                $partida->save();
                break;

            case 'Modificar':
                $partida = Partidas::find($context['id']);
                $partida->nombre = $context['nombre'];
                $partida->clave = $context['clave'];
               $partida->fecha = $context['fecha'] ;
                       $partida->save();
                break;

            case 'Eliminar':
                $partida = Partidas::find($context['id']);
                $partida->delete();
                break;
        }
        return redirect()->route('index_partida');
    }





    function formcrear()
    {
        return view('partida.formcrear');
    }

    function crear(Request $r)
    {
        $context = $r->all();

        // LOGICA DEL NEGOCIO
        $bo = new BoPartida();
        // Esto nos va a permtir para crear un objeto:
        // Con esto le permites al objeto poner los atributos que tu quieras
        $objeto = new \stdClass();
        $objeto->nombre = $context['nombre'];
        $objeto->clave = $context['clave'];
        $jugador = returnjugador();
        $objeto->idjugador = $jugador->id;
        $resultado = $bo->crear($objeto);
        // Si el resultado es distinto al OK entonces te devolvera a una vista
        if ($resultado->status != 'OK') {
            return view('partida.clavevalida');
        }
        return view('partida.creada');
    }

    function formunir()
    {
        return view('partida.formunir');
    }

    function unir(Request $r)
    {
        $context = $r->all();
        $bo = new BoPartida();
        $objeto = new \stdClass();
        $jugador = returnjugador();
        $objeto->idjugador = $jugador->id;
        $objeto->clave = $context['clave'];
        $resultado = $bo->unir($objeto);
        if ($resultado->status == 'OK'){

            $datos['idpartida']=$objeto->idpartida; //se puede modificar 
             $datos['idjugador'] = $jugador->id;
        return view('partida.iniciastepartida')->with($datos);
       
        }
        else
        return redirect()->route('home_jugador')
        ->with('error', $resultado->mensaje);
            // dd()$resultado->mensaje;
            
        // dd($context);  
    }

  

    //AHORA 2- al iniciar ahora inicias en una casilla aleatoria sin jugar del tablero
    function iniciar(Request $r)
    {//retornar all
        $context = $r->all();
        //el turno esta en bo partida
        $bo = new BoPartida();
        $objeto = new \stdClass();
        $objeto->idpartida = $context['idpartida'];
        $bo->iniciar($objeto);
        

        

        return view('partida.iniciastepartida'); 


    }

    // function continuar(Request $r)
    // {
    //     $context = $r->all();
    //     $bo = new BoPartida();
    //     $objeto = new \stdClass();
    //     $jugador = returnjugador();
    //     $objeto->idjugador = $jugador->id;
    //     $objeto->idpartida = $context['idpartida'];
    //     $res = $bo->validar_turno($objeto);
    //     $datos = array();
    //     if ($res->status == 'OK') {
    //         // Si es tu turno
    //         $datos['idpartida'] = $objeto->idpartida;
    //         return view('partida.iniciarturno')->with($datos);
    //     } else {
    //         // No es tu turno
    //         return view('partida.turnoinvalido')->with($datos);
    //     }
    // }
    // function continuar(Request $r)
    // {
    //     $context = $r->all();
    //     $bo = new BoPartida();
    //     $objeto = new \stdClass();
    //     $jugador = returnjugador();
    //     $objeto->idjugador = $jugador->id;
    //     $objeto->idpartida = $context['idpartida'];
    
    //     $res = $bo->validar_turno($objeto);
    //     $datos = array();
    
    //     if ($res->status == 'OK') {
    //         // Si es tu turno
    //         $datos['idpartida'] = $objeto->idpartida;
    //         $datos['idjugador'] = $objeto->idjugador; // Agregamos idjugador
    //         return view('partida.iniciarturno')->with($datos);
    //     } else {
    //         // No es tu turno
    //         return view('partida.turnoinvalido')->with($datos);
    //     }
    // }
    
    function continuar(Request $r)
    {
        $context = $r->all();
        $bo = new BoPartida();
        $objeto = new \stdClass();
        $jugador = returnjugador(); // Obtiene el jugador actual
        $objeto->idjugador = $jugador->id;
        $objeto->idpartida = $context['idpartida'];
    
        // Validamos si es el turno del jugador
        $res = $bo->validar_turno($objeto);
        $datos = array();
    
        if ($res->status == 'OK') {
            // Si es su turno
            $datos['idpartida'] = $objeto->idpartida;
            $datos['idjugador'] = $objeto->idjugador;
    
            // Obtenemos las propiedades del jugador en la partida
            $partidas = $bo->listarPropiedadesJugador($objeto->idjugador, $objeto->idpartida);
    
            // Pasamos las propiedades a la vista
            $datos['partidas'] = $partidas;
    
            return view('partida.iniciarturno')->with($datos);
        } else {
            // No es su turno
            return view('partida.turnoinvalido')->with($datos);
        }
    }
    
///////////////////////////////////////////////////// VERIFICAR CORRECTO ALMACENAMIENTO DE POSICION
    function jugar_turno(Request $r)
    {
        $context = $r->all();
        $objeto = new \stdClass();
        $jugador = returnjugador();
        $objeto->idjugador = $jugador->id;
        $objeto->idpartida = $context['idpartida'];
        $boturno = new BoTurno();
        $res = $boturno->jugar($objeto);
        $datos = array();
        // AQUÍ INDICAMOS AL USUARIO LO QUE VA A VER
        switch ($res->tipo) {
            case 'PROPIEDAD':
                // if($res->ocupado==0){
                if ($res->idjugador == 0) {
                    $datos['casilla'] = $res;
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedaddisponible')->with($datos);
                } else {
                    $datos['casilla'] = $res;
                    //AQUI codigo
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedadocupada')->with($datos);
                }
                break;
            case 'SALIDA':
                return view('turno.salida')->with($datos);
                break;
            case 'OCEANIA':
                return view('turno.oceania')->with($datos);
                break;
            case 'GROELANDIA':
                return view('turno.groenlandia')->with($datos);
                break;
            case 'VISA':
                $datos['casilla'] = $res;
                return view('turno.visa')->with($datos);
                break;
            case 'ADUANA':
                $datos['casilla'] = $res;
                return view('turno.aduana')->with($datos);
                break;
            case 'DEPORTADO':
                $datos['casilla'] = $res;
                return view('turno.deportado')->with($datos);
                break;
            case 'CARTA':
                $datos['casilla'] = $res;
                return view('turno.carta')->with($datos);
                break;
            case 'TELEGRAMA':
                $datos['casilla'] = $res;
                return view('turno.telegrama')->with($datos);
                break;
            case 'LINEA AEREA':
                // if($res->ocupado==0){
                if ($res->idjugador == 0) {
                    $datos['casilla'] = $res;
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedaddisponible')->with($datos);
                } else {
                    $datos['casilla'] = $res;
                    //AQUI codigo
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedadocupada')->with($datos);
                }
                break;
            case 'EMBAJADA':
                // if($res->ocupado==0){
                if ($res->idjugador == 0) {
                    $datos['casilla'] = $res;
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedaddisponible')->with($datos);
                } else {
                    $datos['casilla'] = $res;
                    //AQUI codigo
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedadocupada')->with($datos);
                }
                break;
            case 'CONSULADO':
                // if($res->ocupado==0){
                if ($res->idjugador == 0) {
                    $datos['casilla'] = $res;
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedaddisponible')->with($datos);
                } else {
                    $datos['casilla'] = $res;
                    //AQUI codigo
                    $datos['idpartida'] = $context['idpartida'];
                    return view('turno.propiedadocupada')->with($datos);
                }
                break;
        }
        dd($res);
    }

    function comprar_propiedad(Request $r)
    {
        $bo = new BoControl();
        $context = $r->all();

        $objeto = new \StdClass();
        $jugador = returnjugador();
        $objeto->idjugador = $jugador->id;

        $objeto->idpartida = $context['idpartida'];
        $objeto->idpropiedad = $context['idpropiedad'];
        $objeto->dinero = $context['dinero'];

        $res = $bo->comprar_propiedad($objeto);
        if ($res->perdio == 1) {
            dd('Perdiste');
        } else {
            // dd('Ya la compraste');
            return view('turno.propiedadcomprada');
        }
    }

    function pagar_renta(Request $r)
    {
        $context = $r->all();
        $objeto = new \StdClass();
        $jugador = returnjugador();
        $objeto->idjugador = $jugador->id;
        $objeto->idpartida = $context['idpartida'];
        $objeto->idpropiedad = $context['idpropiedad'];
        $objeto->dinero = $context['dinero'];
        $bo = new BoControl();
        $bo->pagar_renta($objeto);
        dd('Ya pagaste renta');
        // return view()
    }

    public function gestionarPropiedades(Request $request)
    {
        $idpartida = $request->idpartida;
        $idjugador = $request->idjugador;
    
    
        $propiedades = DB::table('partidajugador')
            ->join('propiedadjugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
            ->join('propiedad', 'propiedad.id', '=', 'propiedadjugador.idpropiedad')
            ->join('region', 'region.id', '=', 'propiedad.idregion') // Join con la tabla región
            ->where('partidajugador.idpartida', $idpartida)
            ->where('partidajugador.idjugador', $idjugador)
             ->select(
                 'propiedad.id as id_propiedad',
                 'propiedad.nombre as nombre_propiedad',
                 'propiedadjugador.restaurantes',
                 'propiedadjugador.hotel',
                 'region.nombre as nombre_region', // Nombre de la región
                 'region.color as color'           //Color de la región
             )
            ->get();
        
        return view('turno.catalogopropiedades', [
            'idpartida' => $idpartida,
            'idjugador' => $idjugador,
            'propiedades' => $propiedades
        ]);


    }
    

// protected $boControl;
protected $boControl;
    public function __construct(BoControl $boControl)
    {
        $this->boControl = $boControl;
    }

    public function comprarRestauranteHotel(Request $r)
    {
        $objeto = new \StdClass();
        $objeto->idjugador = $r->idjugador;
        $objeto->idpartida = $r->idpartida;
        $objeto->id_propiedad = $r->id_propiedad;
        $objeto->tipo_compra = $r->tipo_compra;  // 'restaurante' o 'hotel'

        // Llamar a la función que maneja la compra
        $resultado = $this->comprar_restaurante_hotel($objeto);

        // Manejar la respuesta y redirigir según el resultado
        if ($resultado->exito) {
            //return redirect()->route('partida.restaurantcomprado', ['idpartida' => $r->idpartida])->with('success', $resultado->mensaje);
            // dd('Ya la compraste');
            return view('partida.compra');
            // return view('partida.restaurantcomprado');
           //dd('Compraste este Restaurant');
        } else {
            //return redirect()->back()->with('error', $resultado->mensaje);
            return view('partida.error');
            
        }
    }

    public function comprar_restaurante_hotel($objeto)
    {
        $resultado = new \StdClass();

        // Verificar si el jugador está relacionado con la partida correctamente
        $id_partidaxjugador = DB::table('partidajugador')
            ->where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->value('id');

        if (!$id_partidaxjugador) {
            $resultado->mensaje = "Relación entre el jugador y la partida no encontrada.";
            $resultado->exito = false;
            return $resultado;
        }

        // Verificar la propiedad del jugador
        $propiedad_jugador = DB::table('propiedadjugador')
            ->where('idpropiedad', $objeto->id_propiedad)
            ->where('idpartidajugador', $id_partidaxjugador)
            ->first();

        if (!$propiedad_jugador) {
            $resultado->mensaje = "No posees esta propiedad.";
            $resultado->exito = false;
            return $resultado;
        }

        // Obtener información de la propiedad
        $propiedad = DB::table('propiedad')
            ->where('id', $objeto->id_propiedad)
            ->select('r1', 'r2', 'r3', 'hotel as precio_hotel', 'nombre')
            ->first();

        if (!$propiedad) {
            $resultado->mensaje = "Propiedad no encontrada.";
            $resultado->exito = false;
            return $resultado;
        }

        // Lógica para comprar restaurante
        if ($objeto->tipo_compra == 'restaurante') {
            $restaurantes_actuales = $propiedad_jugador->restaurantes;

            if ($restaurantes_actuales >= 3) {
                $resultado->mensaje = "Ya tienes el máximo número de restaurantes.";
                $resultado->exito = false;
                return $resultado;
            }

            // Calcular el precio del siguiente restaurante
            $precio = match ($restaurantes_actuales) {
                0 => $propiedad->r1,
                1 => $propiedad->r2,
                2 => $propiedad->r3,
                default => null,
            };

            if (is_null($precio)) {
                $resultado->mensaje = "Error al calcular el precio del restaurante.";
                $resultado->exito = false;
                return $resultado;
            }

            // Incrementar el número de restaurantes en la propiedad
            DB::table('propiedadjugador')
                ->where('idpropiedad', $objeto->id_propiedad)
                ->where('idpartidajugador', $id_partidaxjugador)
                ->increment('restaurantes');
        }

        // Lógica para comprar hotel
        if ($objeto->tipo_compra == 'hotel') {
            if ($propiedad_jugador->hotel) {
                $resultado->mensaje = "Ya tienes un hotel en esta propiedad.";
                $resultado->exito = false;
                return $resultado;
            }

            // Asignar el precio del hotel
            $precio = $propiedad->precio_hotel;

            // Actualizar el hotel
            DB::table('propiedadjugador')
                ->where('idpropiedad', $objeto->id_propiedad)
                ->where('idpartidajugador', $id_partidaxjugador)
                ->update(['hotel' => 1]);
        }

        // Actualizar dinero
        $modifica_dinero_obj = new \StdClass();
        $modifica_dinero_obj->idjugador = $objeto->idjugador;
        $modifica_dinero_obj->idpartida = $objeto->idpartida;
        $modifica_dinero_obj->dinero = -$precio;

        $boControl = new BoControl();
        $dinero_resultado = $boControl->modifica_dinero($modifica_dinero_obj);

        if ($dinero_resultado->perdio) {
            $resultado->mensaje = "Has perdido el juego por falta de dinero.";
            $resultado->exito = false;
            return $resultado;
        }

        $resultado->mensaje = ucfirst($objeto->tipo_compra) . " comprado exitosamente.";
        $resultado->exito = true;

        return $resultado;
    }


    public function verificarRegionYComprarHotel(Request $request)
    {
        $objeto = new \StdClass();
        $objeto->idjugador = $request->idjugador;
        $objeto->idpartida = $request->idpartida;
        $objeto->id_propiedad = $request->id_propiedad;

        $resultado = new \StdClass();

        // Obtener la propiedad y su región
        $propiedad = DB::table('propiedad')
            ->join('region', 'propiedad.idregion', '=', 'region.id') // Relacionar con la tabla región
            ->where('propiedad.id', $objeto->id_propiedad)
            ->select('propiedad.idregion', 'region.nombre as nombre_region', 'region.color', 'propiedad.nombre', 'propiedad.hotel as precio_hotel')
            ->first();

        if (!$propiedad) {
            $resultado->mensaje = "Propiedad no encontrada.";
            $resultado->exito = false;
            return redirect()->back()->with('error', $resultado->mensaje);
        }

        // Obtener todas las propiedades de la misma región
        $propiedades_region = DB::table('propiedad')
            ->where('idregion', $propiedad->idregion)
            ->pluck('id'); // Extraemos solo los IDs de las propiedades

        // Verificar si el jugador posee todas las propiedades de la región
        $id_partidaxjugador = DB::table('partidajugador')
            ->where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->value('id');

        $propiedades_jugador = DB::table('propiedadjugador')
            ->whereIn('idpropiedad', $propiedades_region)
            ->where('idpartidajugador', $id_partidaxjugador)
            ->pluck('idpropiedad');

        // Comparar si el jugador tiene todas las propiedades de la región
        if ($propiedades_jugador->count() < $propiedades_region->count()) {
            $resultado->mensaje = "No posees todas las propiedades de la región " . $propiedad->nombre_region . " para comprar un hotel.";
            $resultado->exito = false;
            return redirect()->back()->with('error', $resultado->mensaje);
        }

        // Verificar si ya tiene hotel en esta propiedad
        $propiedad_jugador = DB::table('propiedadjugador')
            ->where('idpropiedad', $objeto->id_propiedad)
            ->where('idpartidajugador', $id_partidaxjugador)
            ->first();
            //dd($propiedad_jugador); 
        if (!$propiedad_jugador || $propiedad_jugador->hotel) {
            $resultado->mensaje = "Ya tienes un hotel en esta propiedad o no posees la propiedad.";
            $resultado->exito = false;
            return redirect()->back()->with('error', $resultado->mensaje);
        }

        // Asignar el precio del hotel
        $precio_hotel = $propiedad->precio_hotel;

        // Actualizar la propiedad con el hotel
        DB::table('propiedadjugador')
            ->where('idpropiedad', $objeto->id_propiedad)
            ->update(['hotel' => 1]);

        // Actualizar el dinero del jugador
        $modifica_dinero_obj = new \StdClass();
        $modifica_dinero_obj->idjugador = $objeto->idjugador;
        $modifica_dinero_obj->idpartida = $objeto->idpartida;
        $modifica_dinero_obj->dinero = -$precio_hotel;

        // Usamos la instancia de BoControl para llamar a modifica_dinero
        $boControl = new BoControl();
        $dinero_resultado = $boControl->modifica_dinero($modifica_dinero_obj);

        if ($dinero_resultado->perdio) {
            $resultado->mensaje = "Has perdido el juego por falta de dinero.";
            $resultado->exito = false;
            return redirect()->back()->with('error', $resultado->mensaje);
        }

        $resultado->mensaje = "Hotel comprado exitosamente en la región " . $propiedad->nombre_region . " (color: " . $propiedad->color . ").";
        $resultado->exito = true;

        return view('partida.comprastehotel');
        //dd('Compraste Hotel');
    }


    // public function misPartidas(Request $request)
    // {
    //     $jugador = returnjugador(); // Método que obtiene al jugador actual
    //     $boPartida = new BoPartida();
    //     $partidas = $boPartida->listar_partidas_jugador((object)['idjugador' => $jugador->id]);
    
    //     return view('ruta_vista', [
    //         'nombre' => $jugador->nombre,
    //         'partidas' => $partidas,
    //     ]);
    // }

    
    public function hipotecarpropiedad(Request $request)
{
    // Validar los datos
    $validated = $request->validate([
        'idjugador' => 'required|integer',
        'idpartida' => 'required|integer',
        'idpropiedad' => 'required|integer',
    ]);

    // Crear objeto a partir de los datos validados
    $objeto = (object) $validated;

    // Lógica de hipoteca
    $boControl = new BoControl();
    $montoHipoteca = $boControl->obtener_precio_hipoteca($objeto);

    if ($montoHipoteca) {
        // Actualizar el dinero del jugador restando la hipoteca
        $modifica_dinero_obj = new \StdClass();
        $modifica_dinero_obj->idjugador = $objeto->idjugador;
        $modifica_dinero_obj->idpartida = $objeto->idpartida;
        $modifica_dinero_obj->dinero = $montoHipoteca; // Restamos el monto de la hipoteca

        // Usamos la instancia de BoControl para llamar a modifica_dinero
        $dinero_resultado = $boControl->modifica_dinero($modifica_dinero_obj);

        if ($dinero_resultado->perdio) {
            return redirect()->back()->with('error', 'Has perdido el juego por falta de dinero.');
        }

        return view('partida.hipotecada', compact('montoHipoteca'));
    } else {
        return view('partida.error');
    }
}

// public function verificaHipoteca($idPropiedad) {
//     $propiedad = DB::table('propiedad')->where('id', $idPropiedad)->first();

//     if ($propiedad->hipotecado) {
//         return response()->json(['error' => 'La propiedad ya está hipotecada'], 400);
//     }

//     return response()->json(['success' => 'La propiedad está disponible para hipotecar'], 200);
// }

 

}

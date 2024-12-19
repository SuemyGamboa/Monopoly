<?php

namespace App\BusinessLogic;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Partidas;
use App\Models\PartidaJugador;
use App\Models\Tablero;


class BoPartida
{

    //  $nombre, $clave, $fecha y $idjugador
    // crear($objeto)
    function crear($objeto)
    {

        $resultado = new \stdClass();
        $partida = new Partidas();
        // Tratamiento de los valores de entrada
        $partida->nombre = $objeto->nombre;
        $partida->clave = $objeto->clave;
        $partida->idjugador = $objeto->idjugador;
        $partida->status = 0;
        // $partida->turno = 1;


        // Validar es un select que maneja filtros
        // Me interesa detectar true
        // detectar false"!"
        if ($this->validar_clave($objeto)) {
            //Si ya existe la partida no te dejará usar la misma clave
            // El status te indica si funciono o no
            $resultado->status = 'Not OK';
            $resultado->mensaje = 'Ya existe la clave ' . $objeto->clave;
            return $resultado;
            // dd('Ya existe una partida');
        }
        // La fecha va a hacer un atributo opcional
        // Esto hace que la fecha sea opcional:
        if (!isset($objeto->fecha)) {
            // Si no existe fecha se pone la de hoy
            $objeto->fecha = hoy();
        }
        $partida->fecha = $objeto->fecha;
        $partida->save();

        // Se crea otro objeto para unirse a una partida y mandar los datos
        $objeto2 = new \stdClass();
        $objeto2->idjugador = $objeto->idjugador;
        // Se pone la partida de la recien creada
        $objeto2->idpartida = $partida->id;
        // $objeto2->dinero = 150000;
        $this->unir($objeto2);
        // Si no existe la partida te dejará usar la clave
        // El status te indica si funciono o no
        $resultado->status = 'OK';
        return $resultado;
    }

    function unir($objeto)
    {
        $resultado = new \stdClass();
        // El objeto tiene 3 atributos: $idjugador, $idpartida, $dinero
        $parxjug = new PartidaJugador();
        if (!isset($objeto->idpartida)) {
            $partida = DB::table('partida')->where('clave', $objeto->clave)->first();
            $objeto->idpartida = $partida->id;
        }
        $parxjug->idpartida = $objeto->idpartida;
        $parxjug->idjugador = $objeto->idjugador;
        $parxjug->turno = 0;
        if (!isset($objeto->dinero)) {
            // Si no me dieron dinero, por defaut debes recibir los 150000
            //AHORA 1- al iniciar una partida ahora inicies con 200milpesos
            $objeto->dinero = 200000;
        }
        $parxjug->dinero = $objeto->dinero;

        // Valido si ya está el cupo completo
        // Necesito detectar false
        if (!$this->validar_numparticipantes($objeto)) {
            $resultado->status = 'Not OK';
            $resultado->mensaje = 'Ya se han superado el numero de participantes';
            return $resultado;
        }

        // Valido si ya me incribí
        // Necesito detectar true
        if ($this->validar_participacion($objeto)) {
            $resultado->status = 'Not OK';
            $resultado->mensaje = 'Ya te has incrito previamente a la partida';
            return $resultado;
        }

        $resultado->status = 'OK';
        $parxjug->save();
        $resultado->id = $parxjug->id;
        return $resultado;
    }

    // Validar: Es un Método de lectura que recupera información con base a ciertos filtros
    // y puede devolver false si no encuentra ningún registro y si lo encuentra devuelve el registro

    // Este validar esta definido para que solo recupere un registro
    function validar_clave($objeto)
    {
        $partida = DB::table('partida')->where('clave', $objeto->clave)->first();
        // Si encuentra la partida, devuelve el registro y sino devuelve false
        if ($partida)
            return $partida;
        else
            return false;
    }

    // El objeto tiene los siguientes atributos:
    // idjugador
    // idpartida
    function validar_participacion($objeto)
    {
        $parxjug = DB::table('partidajugador')
            ->where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->first();
        if ($parxjug)
            return $parxjug;
        else
            return false;
    }

    function validar_numparticipantes($objeto)
    {
        $parxjug = DB::table('partidajugador')
            ->where('idpartida', $objeto->idpartida)
            ->get();
        // Preguntamos si el numero de participantes ya se excedio

        if (count($parxjug) >= 6) {
            return false;
        } else {
            return true;
        }

    }


    function listar_partidas_jugador($objeto)
    {
        return DB::table('partida')
            ->join('partidajugador', 'partidajugador.idpartida', '=', 'partida.id')
            ->where('partidajugador.idjugador', $objeto->idjugador)
            ->select(
                'partida.nombre',
                'partida.clave',
                'partida.status',
                'partida.idjugador',
                'partida.id',
                'partida.fecha',
                'partidajugador.dinero',  // Incluimos el dinero
                'partidajugador.turno'   // Incluimos el turno
            )
            ->get();
    }


    //  function listar_partidas_jugador($objeto){
    //      return DB::table('partida')
    //      ->join('partidajugador','partidajugador.idpartida','=','partida.id')
    //      ->where('partidajugador.idjugador', $objeto->idjugador)
    //      ->select(
    //      'partida.nombre',
    //      'partida.clave',
    //      'partida.status',
    //      'partida.idjugador',
    //      'partida.id',
    //      'partida.fecha',
    //     'partidajugador.dinero',  //Incluimos el dinero
    //     'partida.status'  //status
    //      )
    //      ->get();
    //  }



    // function listar_partidas_jugador($objeto) {
    //     $partidas_creadas = DB::table('partida')
    //         ->join('jugador', 'jugador.id', '=', 'partida.idjugador') // Para obtener el nombre del creador
    //         ->where('partida.idjugador', $objeto->idjugador)
    //         ->select(
    //             'partida.nombre',
    //             'partida.clave',
    //             'partida.status',
    //             'partida.idjugador',
    //             'partida.id',
    //             'partida.fecha',
    //             DB::raw('NULL as dinero'), // No aplica dinero para partidas creadas
    //             'jugador.nombre as creador'
    //         );

    //     $partidas_unidas = DB::table('partida')
    //         ->join('partidajugador', 'partidajugador.idpartida', '=', 'partida.id')
    //         ->join('jugador', 'jugador.id', '=', 'partida.idjugador')
    //         ->where('partidajugador.idjugador', $objeto->idjugador)
    //         ->select(
    //             'partida.nombre',
    //             'partida.clave',
    //             'partida.status',
    //             'partida.idjugador',
    //             'partida.id',
    //             'partida.fecha',
    //             'partidajugador.dinero', // Dinero cuando se ha unido
    //             'jugador.nombre as creador'
    //         );

    //     return $partidas_creadas->union($partidas_unidas)->get();
    // }



    // function listar_partidas_jugador($objeto) {
    //     $partidas_creadas = DB::table('partida')
    //         ->join('jugador', 'jugador.id', '=', 'partida.idjugador') // Para obtener el nombre del creador
    //         ->where('partida.idjugador', $objeto->idjugador)
    //         ->select(
    //             'partida.nombre',
    //             'partida.clave',
    //             'partida.status',
    //             'partida.idjugador',
    //             'partida.id',
    //             'partida.fecha',
    //             DB::raw('NULL as dinero'), // No aplica dinero para partidas creadas
    //             'jugador.nombre as creador'
    //         );

    //     $partidas_unidas = DB::table('partida')
    //         ->join('partidajugador', 'partidajugador.idpartida', '=', 'partida.id')
    //         ->join('jugador', 'jugador.id', '=', 'partida.idjugador')
    //         ->where('partidajugador.idjugador', $objeto->idjugador)
    //         ->select(
    //             'partida.nombre',
    //             'partida.clave',
    //             'partida.status',
    //             'partida.idjugador',
    //             'partida.id',
    //             'partida.fecha',
    //             'partidajugador.dinero', // Dinero cuando se ha unido
    //             'partidajugador.turno'
    //         );

    //     return $partidas_creadas->union($partidas_unidas)->get();
    // }



    // AHORA 2- al iniciar ahora inicias en una casilla aleatoria sin jugar del tablero
    //El objeto tiene el atributo de idjpartida
    // function iniciar($objeto)
    // {
    //     // 1.- Cambiar el status de la partida en 1 y le pongo el turno 1
    //     $partida = Partidas::find($objeto->idpartida);
    //     //STATUS indica si esta activo
    //     $partida->status = 1;
    //     //este es el turno que les tocó
    //     $partida->turno = 1;
    //     $partida->save();

    //     // $rposicion = PartidaJugador::find($objetodos->posicion);
    //     // //STATUS indica si esta activo
    //     // $rposicion->posicion = tirar_dados();
    //     // //este es el turno que les tocó
    //     // $rposicion->save();

    //     // 2.- Asignar los turnos a los jugadores
    //     // 2.1.- Obtener cuantos jugadores tiene la partida
    //     $parxjug = DB::table('partidajugador')
    //         ->where('idpartida', $objeto->idpartida)
    //         ->get();
    //     $numero_jugadores = count($parxjug);
    //     // 2.2.- Un arreglo de n numeros de acuerdo al numero de jugadores
    //     $turnos = range(1, $numero_jugadores);
    //     shuffle($turnos);
    //     // dd($turnos);
    //     $jugadores = PartidaJugador::where('idpartida', $objeto->idpartida)

    //     ->get();


    //     // 4.- Obtener todas las posiciones disponibles del tablero
    //     $posiciones_tablero = DB::table('tablero')->pluck('id'); // Asumiendo que 'id' es la clave de las casillas
    //     $posiciones_tablero = $posiciones_tablero->shuffle();
    //     // 2.3.- Asignar los turnos a los jugadores
    //     // También les asigno la posición inicial
    //     ///////////////////////////////////////// regla2////////////////
    //     $indice_turnos = 0;
    //     foreach ($jugadores as $jugador) { //indico que el turno será mi instancia indiceturno
    //         $jugador->turno = $turnos[$indice_turnos];
    //         //original
    //         $jugador->turno = 1;
    //         //es autoincrementable 
    //         // 2- al iniciar ahora inicias en una casilla aleatoria sin jugar del tablero

    //         $indice_turnos++;
    //         $jugador->save();
    //     }
    // }



    function iniciar($objeto)
    {
        // 1.- Cambiar el status de la partida en 1 y le pongo el turno 1
        $partida = Partidas::find($objeto->idpartida);
        $partida->status = 1;
        $partida->turno = 1;
        $partida->save();

        // 2.- Asignar los turnos a los jugadores
        // 2.1.- Obtener cuantos jugadores tiene la partida
        $parxjug = DB::table('partidajugador')
            ->where('idpartida', $objeto->idpartida)
            ->get();
        $numero_jugadores = count($parxjug);

        // 2.2.- Un arreglo de n numeros de acuerdo al numero de jugadores
        $turnos = range(1, $numero_jugadores);
        shuffle($turnos);
        // dd($turnos);

        // 2.3.- Asignar los turnos a los jugadores
        // También les asigno la posición inicial
        $jugadores = Partidajugador::where('idpartida', $objeto->idpartida)->get();




        // 4.- Obtener todas las posiciones disponibles del tablero
        $posiciones_tablero = DB::table('tablero')->pluck('id'); // Asumiendo que 'id' es la clave de las casillas
        $posiciones_tablero = $posiciones_tablero->shuffle();
        $indice_turnos = 0;
        foreach ($jugadores as $jugador) {
            $jugador->turno = $turnos[$indice_turnos];
            // $jugador->turno=1;
            $indice_turnos++;
            $jugador->save();
        }
    }
    //////////////////////////////////////////////////
    // El objeto tiene los siguientes atributos:
    // idjugador
    // idpartida
    // function validar_turno($objeto)
    // {
    //     $resultado = new \stdClass();

    //     // Buscar la partida
    //     $partida = Partidas::find($objeto->idpartida);

    //     // Validar si se encontró la partida
    //     if (!$partida) {
    //         $resultado->status = 'Error';
    //         $resultado->info = 'Partida no encontrada';
    //         return $resultado;
    //     }
    //     ////////////////////////////////////////7 este es el turno ////////////////
    //     // Buscar al jugador en la partida
    //     $parxjug = DB::table('partidajugador')
    //         ->where('idjugador', $objeto->idjugador)
    //         ->where('idpartida', $objeto->idpartida)
    //         ->first();

    //     // Validar si el jugador está en la partida
    //     if (!$parxjug) {
    //         $resultado->status = 'Error';
    //         $resultado->info = 'Jugador no encontrado en la partida';
    //         return $resultado;
    //     }

    //     // Comparar los turnos de la partida y del jugador
    //     if ($partida->turno == $parxjug->turno) {
    //         $resultado->status = 'OK';
    //         $resultado->info = $parxjug;
    //     } else {
    //         $resultado->status = 'Not OK';
    //     }

    //     return $resultado;
    // }

    function validar_turno($objeto)
    {
        $resultado = new \stdClass();
        // Si el turno que tiene la partida es igual al turno que tiene el jugador en la partida
        $partida = Partidas::find($objeto->idpartida);
        // Obtener el turno que tiene el jugador en la partida
        $parxjug = DB::table('partidajugador')
            ->where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->first();
        // Comparo los turnos de la partida y del jugador
        if ($partida->turno == $parxjug->turno) {
            $resultado->status = 'OK';
            $resultado->info = $parxjug;
        } else {
            $resultado->status = 'Not OK';
        }
        return $resultado;
    }






    // SELECT jugador.nombre AS jugador,
//     propiedad.nombre AS propiedad,
//     propiedad.precio,
//     propiedad.renta,
//     propiedad.hotel,
//     propiedad.restaurant,
//     propiedadjugador.hotel,
//     propiedadjugador.restaurantes
// FROM 
//     propiedadjugador
// JOIN 
//     jugador ON propiedadjugador.idjugador = jugador.id
// JOIN 
//     propiedad ON propiedadjugador.id = propiedad.id
// WHERE 
//     jugador.id = 2  AND partida.id = 1;


    function listarPropiedadesJugador($idJugador, $idPartida)
    {
        $propiedades = DB::table('propiedadjugador')
            ->join('jugador', 'propiedadjugador.idjugador', '=', 'jugador.id')
            ->join('propiedad', 'propiedadjugador.idpropiedad', '=', 'propiedad.id')
            ->join('partida', 'propiedadjugador.idpartida', '=', 'partida.id')
            ->where('jugador.id', $idJugador)
            ->where('partida.id', $idPartida)
            ->select(
                'jugador.id as idjugador', // Agrega este campo
                'propiedad.nombre as propiedad',
                'propiedad.precio as precio',
                'propiedad.renta as renta',
                'propiedad.hotel as costo_hotel',
                'propiedad.restaurant as costo_restaurant',
                'propiedadjugador.hotel as hoteles_adquiridos',
                'propiedadjugador.restaurantes as restaurantes_adquiridos',
                'propiedadjugador.idpartida',
                'propiedadjugador.idpropiedad as id' // Asegura que este campo también esté presente
            )
            ->get();

        return $propiedades;
    }





}
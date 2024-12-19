<?php
namespace App\BusinessLogic;
use Illuminate\Support\Facades\DB;
use App\Models\Partidas;
use App\Models\Jugadores;
use App\Models\Tablero;
use App\Models\Propiedad;
use App\Models\PartidaJugador;
use App\Models\PropiedadJugador;
use App\Models\Carta;
use App\Models\Telegrama;

use App\BusinessLogic\BoTurno;

class BoControl
{
    // El objeto tiene los siguientes atributos:
    // Casilla
    // Puedo caer en:
    // Propiedad
    // Aeropuerto
    // Salida | Mexico
    // Carta | Telegrama
    // Deportado
    // Oceania
    // Embajada | Consulado
    // Visa
    // Aduana
    // Greolandia
    function info_casilla($objeto)
    {
        $boturno = new BoTurno();
        $resultado = new \stdClass();

        $casilla = Tablero::where('posicion', $objeto->posicion)->first();
        $resultado->tipo = $casilla->tipo;
        switch ($casilla->tipo) {
            case 'PROPIEDAD':
                // Encuentra la ID  /Dado un jugador en una partida quiero saber si tiene una propiedad en especifico/
                $propiedad = Propiedad::find($casilla->idorigen);
                $pertenece = DB::table('propiedadjugador')
                    ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
                    // ->where('partidajugador.idjugador', $objeto->idjugador)
                    ->where('partidajugador.idpartida', $objeto->idpartida)
                    ->where('propiedadjugador.idpropiedad', $propiedad->id)
                    ->select(
                        'partidajugador.idpartida',
                        'partidajugador.idjugador',
                        'propiedadjugador.idpropiedad',
                        'propiedadjugador.tipo',
                        'propiedadjugador.status',
                        'propiedadjugador.restaurantes',
                        'propiedadjugador.hotel'
                    )
                    ->first();
                $resultado->info_propiedad = $propiedad;
                if ($pertenece) {
                    $resultado->info_pertenece = $pertenece;
                    //Alguien la tiene:
                    $resultado->idjugador = $pertenece->idjugador;
                    //Si yo lo tengo:
                    if ($pertenece->idjugador == $objeto->idjugador) {
                        $resultado->belong = 1;
                    } else {
                        $resultado->belong = 0;
                        $objeto3 = new \stdClass();
                        $objeto3->idpropiedad = $propiedad->id;
                        $objeto3->idjugador = $pertenece->idjugador;
                        $objeto3->idpartida = $objeto->idpartida;
                        $precio_nuevo = $this->obtener_precio($objeto3);
                        $resultado->info_propiedad->precio = $precio_nuevo->precio;
                    }
                } else {
                    //Nadie la tiene:
                    $resultado->idjugador = 0;
                }
                break;
            case 'SALIDA':
                //Aumento el dinero del jugador en 20000 
                $objeto->dinero = 20000;
                $this->modifica_dinero($objeto);
                $boturno->cerrar($objeto);
                $resultado->mensaje = 'Caiste en la salida';
                break;
            case 'OCEANIA':
                $resultado->mensaje = 'Caiste en oceania';
                $boturno->cerrar($objeto);
                break;
            case 'VISA':
                //Me quitan el dinero del jugador en 10000 
                $objeto->dinero = -10000;
                $res = $this->modifica_dinero($objeto);
                $resultado->perdio = $res->perdio;
                $boturno->cerrar($objeto);
                $resultado->mensaje = 'Caiste en visa';
                break;
            case 'ADUANA':
                //Me quitan el dinero del jugador en 10000
                //1.-Obtener el dinero del jugador
                $par = PartidaJugador::where('idjugador', $objeto->idjugador)
                    ->where('idpartida', $objeto->idpartida)
                    ->first();
                //2.-Calculo el 10% del dinero del jugador 
                $objeto->dinero = $par->dinero * -.1;
                $res = $this->modifica_dinero($objeto);
                $resultado->perdio = $res->perdio;
                $boturno->cerrar($objeto);
                $resultado->mensaje = 'Caiste en aduana';
                break;
            case 'DEPORTADO':
                //El efecto es te quito 5000 y te mando a Groelandia //1.-Le quito al jugador 5000
                $resultado->mensaje = 'Caiste en deportado';
                $objeto->dinero = -5000;
                $res = $this->modifica_dinero($objeto);
                //2.-Cambio la posicion del jugador en el tablero a Groelandia (31) 
                $parxjug = PartidaJugador::where('idjugador', $objeto->idjugador)
                    ->where('idpartida', $objeto->idpartida)
                    ->first();
                $parxjug->posicion = 31;
                $parxjug->save();
                $resultado->perdio = $res->perdio;
                $boturno->cerrar($objeto);
                break;
            case 'GROELANDIA':
                $resultado->mensaje = 'Caiste en groelandia';
                $boturno->cerrar($objeto);
                break;
            case 'CARTA':
                //1.-Recuperar de forma aleatoria una carta de la bd
                //ESTE ES QUE SE USA POR DEFECTO Y SIEMPRE

                /////////////////////////////////////////// PARCHE //////////////////////////////////////////////////////////////
                // CODIGO ORIGINAL RANDOM CARTAS
                //$carta = Carta::inRandomOrder()->limit(1)->first();

                // ID PARA  LAS CARTAS,(PARCHE) numero de cartas, aparte es la posicion del tablero
                $carta = Carta::find(4);
                //////////////////////////////////////////////////////////////////////////////////////////
                $resultado->mensaje = $carta->mensaje;

                //2.-Ejecutar el efecto de la carta
                switch ($carta->id) {
                    case 1:
                        //Aumentar en 50,000 el dinero del jugador en la partida 
                        $objeto->dinero = 50000;
                        $this->modifica_dinero($objeto);
                        break;
                    case 2:
                        //Quitarle todo el dinero de esta partida al jugador 
                        //1.-Obtener el dinero que tiene el jugador en la partida 
                        $par = PartidaJugador::where('idjugador', $objeto->idjugador)
                            ->where('idpartida', $objeto->idpartida)
                            ->first();
                        $objeto->dinero = ($par->dinero * -1) - 1;
                        $res = $this->modifica_dinero($objeto);
                        $resultado->perdio = $res->perdio;
                        break;
                    case 3:
                        //Quitarle a todos los jugadores de la partida 10000
                        //1.-Recuperar a todos los jugadores de la partida a excepcion del jugador del turno
                        $par = PartidaJugador::where('idjugador', '!=', $objeto->idjugador)
                            ->where('idpartida', $objeto->idpartida)
                            ->get();
                        //2.-Restarle a todos los jugadores recuperados 10000
                        foreach ($par as $elemento) {
                            $objeto2 = new \StdClass();
                            $objeto2->idpartida = $objeto->idpartida;
                            $objeto2->idjugador = $elemento->idjugador;
                            $objeto2->dinero = -10000;
                            $this->modifica_dinero($objeto2);
                        }

                    break;
                     case 4:
                               // Obtienes el turno del jugador actual
                                $jugador_actual = DB::table('partidajugador')
                                    ->where('idpartida', $objeto->idpartida)
                                    ->where('idjugador', $objeto->idjugador)
                                    ->first();
    
                                // Verificar si el jugador está inactivo (saldo 0 y status 0)
                             /*    if ($jugador_actual->dinero <= 0 && $jugador_actual->status == 0) {
                                    dd([
                                        'mensaje' => "El jugador no tiene suficiente dinero para continuar con el juego.",
                                        'tipo' => $tipo
                                    ]);
                                } */
    
                                //  Obtener el siguiente turno
                                $siguiente_turno = DB::table('partidajugador')
                                    ->where('idpartida', $objeto->idpartida)
                                    ->where('turno', $jugador_actual->turno + 1)  // Usar el turno del jugador actual
                                    ->first();
    
                                // Obtener una propiedad ocupada de otro jugador (siguiente jugador)
                                $propiedad_ocupada = DB::table('propiedadjugador')
                                    ->join('partidajugador', 'propiedadjugador.idpartidajugador', '=', 'partidajugador.id')
                                    ->join('propiedad', 'propiedadjugador.idpropiedad', '=', 'propiedad.id') // Join con la tabla propiedad
                                    ->where('partidajugador.idpartida', $objeto->idpartida)
                                    ->where('partidajugador.idjugador', $siguiente_turno->idjugador)  // Filtrar solo las propiedades del siguiente jugador
                                    ->select(
                                        'propiedadjugador.idpropiedad',
                                        'partidajugador.idjugador',
                                        'propiedad.renta', // Aquí obtenemos la renta desde la tabla propiedad
                                        'propiedad.id',
                                        'partidajugador.idjugador as propietario_id'
                                    )
                                    ->inRandomOrder() // Selección aleatoria
                                    ->first();
    
                                // 4. Calcular el doble de la renta
                                $renta_doble = $propiedad_ocupada->renta * 2;
    
                                // 5. Obtener el saldo del jugador actual
                                $jugador_actual = DB::table('partidajugador')
                                    ->where('idjugador', $objeto->idjugador)
                                    ->where('idpartida', $objeto->idpartida)
                                    ->first();
    
                                $nuevo_saldo = $jugador_actual->dinero - $renta_doble;
    
                                // 6. Validar si el saldo es negativo y ajustar
                                if ($nuevo_saldo < 0) {
                                    $nuevo_saldo = 0; // Limitar a 0 si el saldo es negativo
                                    DB::table('partidajugador')
                                        ->where('id', $jugador_actual->id)
                                        ->update(['dinero' => $nuevo_saldo, 'status' => 0]); // Cambiar el status a 0
                                } else {
                                    // 7. Actualizar el saldo del jugador actual
                                    DB::table('partidajugador')
                                        ->where('id', $jugador_actual->id)
                                        ->update(['dinero' => $nuevo_saldo]);
                                }
    
                                // 8. Cambiar la posición del jugador al id de la propiedad ocupada
                                $pxjug = PartidaJugador::where('idjugador', $objeto->idjugador)
                                    ->where('idpartida', $objeto->idpartida)
                                    ->first();
    
                                // Cambiar la posición del jugador a la propiedad ocupada
                                $pxjug->posicion = $propiedad_ocupada->id;
                                $pxjug->save();
    
                                // 9. Sumar el dinero de la renta al propietario de la propiedad ocupada
                                $propietario = PartidaJugador::where('idjugador', $propiedad_ocupada->propietario_id)
                                    ->where('idpartida', $objeto->idpartida)
                                    ->first();
    
                                // Aumentar el saldo del propietario con el dinero de la renta
                                $propietario->dinero += $renta_doble;
                                $propietario->save();
    
                                // 10. Mensaje de confirmación y pasar $tipo a la vista
                                /* dd([
                                    'mensaje' => "Has pagado el doble de la renta en una propiedad ocupada. Monto pagado: $$renta_doble. Has sido trasladado a la propiedad con ID: {$propiedad_ocupada->id}.",
                                    'nuevo_saldo' => $nuevo_saldo,
                                    'tipo' => $tipo
                                ]);
    
    
                                */
                        break;
                
                
                    // case 4:
                    //     // Obtienes el turno del jugador actual
                    //     $jugador_actual = DB::table('partidajugador')
                    //         ->where('idpartida', $objeto->idpartida)
                    //         ->where('idjugador', $objeto->idjugador)
                    //         ->first();
                    
                    //     // Obtener el siguiente turno
                    //     $siguiente_turno = DB::table('partidajugador')
                    //         ->where('idpartida', $objeto->idpartida)
                    //         ->where('turno', $jugador_actual->turno + 1)
                    //         ->first();
                    
                    //     // Verificar si existe un siguiente turno y propiedades ocupadas
                    //     if ($siguiente_turno) {
                    //         $propiedad_ocupada = DB::table('propiedadjugador')
                    //             ->join('partidajugador', 'propiedadjugador.idpartidajugador', '=', 'partidajugador.id')
                    //             ->join('propiedad', 'propiedadjugador.idpropiedad', '=', 'propiedad.id')
                    //             ->where('partidajugador.idpartida', $objeto->idpartida)
                    //             ->where('partidajugador.idjugador', $siguiente_turno->idjugador)
                    //             ->select(
                    //                 'propiedadjugador.idpropiedad',
                    //                 'partidajugador.idjugador',
                    //                 'propiedad.renta',
                    //                 'propiedad.id',
                    //                 'partidajugador.idjugador as propietario_id'
                    //             )
                    //             ->inRandomOrder()
                    //             ->first();
                    
                    //         // Si hay una propiedad ocupada
                    //         if ($propiedad_ocupada) {
                    //             // Calcular el doble de la renta
                    //             $renta_doble = $propiedad_ocupada->renta * 2;
                    
                    //             // Obtener el saldo del jugador actual
                    //             $nuevo_saldo = $jugador_actual->dinero - $renta_doble;
                    
                    //             // Ajustar el saldo
                    //             if ($nuevo_saldo < 0) {
                    //                 $nuevo_saldo = 0;
                    //                 DB::table('partidajugador')
                    //                     ->where('id', $jugador_actual->id)
                    //                     ->update(['dinero' => $nuevo_saldo, 'status' => 0]);
                    //             } else {
                    //                 DB::table('partidajugador')
                    //                     ->where('id', $jugador_actual->id)
                    //                     ->update(['dinero' => $nuevo_saldo]);
                    //             }
                    
                    //             // Actualizar la posición del jugador
                    //             $pxjug = PartidaJugador::where('idjugador', $objeto->idjugador)
                    //                 ->where('idpartida', $objeto->idpartida)
                    //                 ->first();
                    //             $pxjug->posicion = $propiedad_ocupada->id;
                    //             $pxjug->save();
                    
                    //             // Actualizar el saldo del propietario
                    //             $propietario = PartidaJugador::where('idjugador', $propiedad_ocupada->propietario_id)
                    //                 ->where('idpartida', $objeto->idpartida)
                    //                 ->first();
                    //             $propietario->dinero += $renta_doble;
                    //             $propietario->save();
                    //         } else {
                    //             // No hay propiedades ocupadas, redirigir a la vista
                              
                    //             return view('turno.carta4error');
                    //         }
                    //     } else {
                    //         // No hay un siguiente turno válido, redirigir a la vista
                    //         $datos['casilla'] = (object) ['mensaje' => 'No hay jugadores activos en la siguiente posición.'];
                    //         return view('turno.carta')->with($datos);
                    //     }
                    
                    //     break;
                    
                
                }
                $boturno->cerrar($objeto);
                break;

            case 'TELEGRAMA':
                //1.-Recuperar de forma aleatoria un telegrama de la bd 
                ///////////////////////////////////////////////////////////////////////////////////////////
                /// CODIGO ORIGINAL TELEGRAMA RANDOM
                $telegrama = Telegrama::inRandomOrder()->limit(1)->first();

                //PARCHE PARA PROBAR 3 CASOS
                // $telegrama = Telegrama::find(1);
                //////////////////////////////////////////////////////////////////
                $resultado->mensaje = $telegrama->mensaje;

                switch ($telegrama->id) {
                    case 1:
                        //Obtener una propiedad aleatoria de un jugador aleatorio

                        //1.-Obtener de manera aleatoria de la tabla PropiedadxJugador un registro
                        $propiedad = DB::table('partidajugador')
                            ->join('propiedadjugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
                            ->where('partidajugador.idpartida', $objeto->idpartida)
                            ->where('partidajugador.idjugador', '!=', $objeto->idjugador)
                            ->select(
                                'propiedadjugador.id'
                                ,
                                'partidajugador.idjugador'
                                ,
                                'partidajugador.idpartida'
                                ,
                                'propiedadjugador.idpropiedad'
                            )
                            ->inRandomOrder()->limit(1)->first();
                        //2.-Obtener la idpartidaxjugador del jugador del turno en la partida
                        $par = PartidaJugador::where('idjugador', $objeto->idjugador)
                            ->where('idpartida', $objeto->idpartida)
                            ->first();
                        //3.-Modificar el registro de PropiedadxJugador en el campo partidaxjugador
                        $proxjug = PropiedadJugador::find($propiedad->id);
                        $proxjug->idpartidajugador = $par->id;
                        $proxjug->save();
                        break;
                    case 2:
                        //Aumentar una cantidad aleatoria de dinero al jugador
                        $aumento = rand(1000, 10000);
                        $objeto->dinero = $aumento;
                        $this->modifica_dinero($objeto);
                        break;
                    case 3:
                        //Al siguiente jugador en turno quitarle 1000
                        //1.-Recuperar el turno del jugador en turno en la partida
                        $par = PartidaJugador::where('idjugador', $objeto->idjugador)
                            ->where('idpartida', $objeto->idpartida)
                            ->first();
                        //2.-Sumarle a ese turno 1
                        $turno_nuevo = $par->turno + 1;
                        //3.-Obtener el numero de jugadores de la partida
                        $jugadores = PartidaJugador::where('idpartida', $objeto->idpartida)
                            ->get();
                        //4.-Verificar si el numero que obtenemos es mayor al numero de jugadores
                        if ($turno_nuevo > count($jugadores)) {
                            //4.1 Si es asi el turno es 1
                            $turno_nuevo = 1;
                        }
                        //5.-Obtener el jugador que le toco el turno en la partida 
                        $jugador_quitar = PartidaJugador::where('turno', $turno_nuevo)
                            ->where('idpartida', $objeto->idpartida)
                            ->first();
                        //6.-Le quitamos 1000
                        $objeto2 = new \StdClass();
                        $objeto2->idpartida = $objeto->idpartida;
                        $objeto2->idjugador = $jugador_quitar->idjugador;
                        $objeto2->dinero = -1000;
                        $this->modifica_dinero($objeto2);
                        // 
                        break;
                }
                $boturno->cerrar($objeto);
                break;
            case 'LINEA AEREA':
                $propiedad = Propiedad::find($casilla->idorigen);
                $pertenece = DB::table('propiedadjugador')
                    ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
                    // ->where('partidaxjugador.idjugador', $objeto->idjugador)
                    ->where('partidajugador.idpartida', $objeto->idpartida)
                    ->where('propiedadjugador.idpropiedad', $propiedad->id)
                    ->select(
                        'partidajugador.idpartida',
                        'partidajugador.idjugador',
                        'propiedadjugador.idpropiedad',
                        'propiedadjugador.tipo',
                        'propiedadjugador.status',
                    )
                    ->first();
                $resultado->info_propiedad = $propiedad;
                if ($pertenece) {
                    $resultado->info_pertenece = $pertenece;
                    //Alguien la tiene:
                    $resultado->idjugador = $pertenece->idjugador;
                    //Si yo lo tengo:
                    if ($pertenece->idjugador == $objeto->idjugador) {
                        $resultado->belong = 1;
                    } else {
                        $resultado->belong = 0;
                        $objeto3 = new \stdClass();
                        $objeto3->idpropiedad = $propiedad->id;
                        $objeto3->idjugador = $pertenece->idjugador;
                        $objeto3->idpartida = $objeto->idpartida;
                        $precio_nuevo = $this->obtener_precio_aerolinea($objeto3);
                        $resultado->info_propiedad->precio = $precio_nuevo->precio;
                    }
                } else {
                    //Nadie la tiene:
                    $resultado->idjugador = 0;
                }
            case 'EMBAJADA':
                $propiedad = Propiedad::find($casilla->idorigen);
                $pertenece = DB::table('propiedadjugador')
                    ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
                    // ->where('partidaxjugador.idjugador', $objeto->idjugador)
                    ->where('partidajugador.idpartida', $objeto->idpartida)
                    ->where('propiedadjugador.idpropiedad', $propiedad->id)
                    ->select(
                        'partidajugador.idpartida',
                        'partidajugador.idjugador',
                        'propiedadjugador.idpropiedad',
                        'propiedadjugador.tipo',
                        'propiedadjugador.status',
                    )
                    ->first();
                $resultado->info_propiedad = $propiedad;
                if ($pertenece) {
                    $resultado->info_pertenece = $pertenece;
                    //Alguien la tiene:
                    $resultado->idjugador = $pertenece->idjugador;
                    //Si yo lo tengo:
                    if ($pertenece->idjugador == $objeto->idjugador) {
                        $resultado->belong = 1;
                    } else {
                        $resultado->belong = 0;
                        $objeto3 = new \stdClass();
                        $objeto3->idpropiedad = $propiedad->id;
                        $objeto3->idjugador = $pertenece->idjugador;
                        $objeto3->idpartida = $objeto->idpartida;
                        $precio_nuevo = $this->obtenerprecioembajadaconsulado($objeto3);
                        $resultado->info_propiedad->precio = $precio_nuevo->precio;
                    }
                } else {
                    //Nadie la tiene:
                    $resultado->idjugador = 0;
                }
            case 'CONSULADO':
                $propiedad = Propiedad::find($casilla->idorigen);
                $pertenece = DB::table('propiedadjugador')
                    ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
                    // ->where('partidaxjugador.idjugador', $objeto->idjugador)
                    ->where('partidajugador.idpartida', $objeto->idpartida)
                    ->where('propiedadjugador.idpropiedad', $propiedad->id)
                    ->select(
                        'partidajugador.idpartida',
                        'partidajugador.idjugador',
                        'propiedadjugador.idpropiedad',
                        'propiedadjugador.tipo',
                        'propiedadjugador.status',
                    )
                    ->first();
                $resultado->info_propiedad = $propiedad;
                if ($pertenece) {
                    $resultado->info_pertenece = $pertenece;
                    //Alguien la tiene:
                    $resultado->idjugador = $pertenece->idjugador;
                    //Si yo lo tengo:
                    if ($pertenece->idjugador == $objeto->idjugador) {
                        $resultado->belong = 1;
                    } else {
                        $resultado->belong = 0;
                        $objeto3 = new \stdClass();
                        $objeto3->idpropiedad = $propiedad->id;
                        $objeto3->idjugador = $pertenece->idjugador;
                        $objeto3->idpartida = $objeto->idpartida;
                        $precio_nuevo = $this->obtenerprecioembajadaconsulado($objeto3);
                        $resultado->info_propiedad->precio = $precio_nuevo->precio;
                    }
                } else {
                    //Nadie la tiene:
                    $resultado->idjugador = 0;
                }
        }

        // // $resultado->tipo='Propiedad';

        return $resultado;
    }

    // En partida x jugador esta la columna dinero
    function modifica_dinero($objeto)
    {
        $resultado = new \StdClass();
        //1.-Modifico el campo dinero de PartidaXJugador
        $par = PartidaJugador::where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->first();
        $par->dinero = $par->dinero + $objeto->dinero;
        $resultado->perdio = 0;
        if ($par->dinero < 0) {
            $resultado->perdio = 1;
            $par->dinero = 0;
            $par->status = 0;
        }
        //2.- Si el dinero quedo en 0 edito el campo status de PartidaxJugador

        $par->save();
        return $resultado;
    }


    // function comprar_propiedad($objeto){
    //     $boturno=new BoTurno();
    //     //1.-Agregar la propiedad al jugador en la partida 
    //     $parxjug=new PropiedadJugador();
    //     $parxjug->idpropiedad=$objeto->idpropiedad;
    //     $parxjug->restaurantes=0;
    //     $parxjug->hotel=0;
    //     //Status 1 si estoy bieb y 0 si estaba hipotecada
    //     $parxjug->status=1;
    //     $parxjug->tipo='PROPIEDAD';

    //     $par=PartidaJugador::where('idjugador', $objeto->idjugador)
    //     ->where('idpartida', $objeto->idpartida)
    //     ->first();
    //     $parxjug->idpartidajugador=$par->id;
    //     $parxjug->save();
    //     //2.-Descontar el dinero
    //     $objeto->dinero=$objeto->dinero*-1;
    //     //Cierro el turno
    //     $boturno->cerrar($objeto);
    //     return $this->modifica_dinero ($objeto);
    // }
    function comprar_propiedad($objeto)
    {
        $boturno = new BoTurno();

        // Crear una nueva instancia de PropiedadJugador
        $parxjug = new PropiedadJugador();
        $parxjug->idpropiedad = $objeto->idpropiedad;
        $parxjug->restaurantes = 0;
        $parxjug->hotel = 0;
        $parxjug->status = 1; // Propiedad activa
        $parxjug->tipo = 'PROPIEDAD';

        // Obtener la relación partida-jugador
        $par = PartidaJugador::where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->first();

        if (!$par) {
            throw new \Exception('No se encontró un registro válido en PartidaJugador.');
        }

        $parxjug->idpartidajugador = $par->id;
        $parxjug->idjugador = $objeto->idjugador; // Asignar idjugador
        $parxjug->idpartida = $objeto->idpartida; // Asignar idpartida

        // Guardar el modelo
        $parxjug->save();

        // Descontar el dinero del jugador
        $objeto->dinero = $objeto->dinero * -1;
        $boturno->cerrar($objeto);

        return $this->modifica_dinero($objeto);
    }



    function pagar_renta($objeto)
    {
        //1.-Quito el dinero el jugador del turno
        $objeto->dinero = $objeto->dinero * -1;
        $res = $this->modifica_dinero($objeto);


        $boturno = new BoTurno();
        $boturno->cerrar($objeto);
        // dd ($objeto);
        $info_pertenece = DB::table('propiedadjugador')
            ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
            ->where('partidajugador.idpartida', $objeto->idpartida)
            // ->where('propiedadxjugador.idpropiedad', $objeto->idjugador)
            ->where('propiedadjugador.idpropiedad', $objeto->idpropiedad)
            ->select(
                'partidaxjugador.idjugador'
                ,
                'propiedadjugador.tipo'
                ,
                'propiedadjugador.status'
                ,
                'propiedadjugador.restaurantes'
                ,
                'propiedadjugador.hotel'
            )
            ->first();
        // dd($info_pertenece);
        $objeto->idjugador = $info_pertenece->idjugador;
        $objeto->dinero = $objeto->dinero * -1;
        $this->modifica_dinero($objeto);
    }


    function obtener_precio($objeto)
    {
        $resultado = new \StdClass();
        $resultado->precio = 0;
        //Recuperar la informacion de pertenencia
        $pertenece = DB::table('propiedadjugador')
            ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
            ->where('partidajugador.idpartida', $objeto->idpartida)
            ->where('propiedadjugador.idpropiedad', $objeto->idpropiedad)
            ->where('tipo', 'PROPIEDAD')
            ->where('partidajugador.idjugador', $objeto->idjugador)
            ->select(
                'propiedadjugador.tipo',
                'propiedadjugador.status'
                ,
                'propiedadjugador.restaurantes'
                ,
                'propiedadjugador.hotel'
            )
            ->first();
        //Recuperar la informacion de la propiedad
        $propiedad = Propiedad::find($objeto->idpropiedad);
        //1.-Si tiene hotel
        if ($pertenece->hotel == 1) {
            //1.1 SI entonces es el precio del hotel $resultado->precio=$propiedad->hotel
            $resultado->precio = $propiedad->hotel;
        } else {
            //1.2 NO
            //1.2.1 Si tiene restaurantes
            if ($pertenece->restaurantes != 0) {
                //1.2.1.1 SI dependiendo del numero le cobro la renta correspondiente 
                switch ($pertenece->restaurantes) {
                    case 1:
                        $resultado->precio = $propiedad->r1;
                        break;
                    case 2:
                        $resultado->precio = $propiedad->r2;
                        break;
                    case 3:
                        $resultado->precio = $propiedad->r3;
                        break;
                }
            } else {

                //Obtenemos todas las propiedades de la misma region de la
                $propiedades = Propiedad::where('idregion', $propiedad->idregion)->get();

                //Si vale 0 significa que tenga todas y si vale 1 que por lo menos NO TIENE una propiedad
                $bandera = 0;
                foreach ($propiedades as $elemento) {
                    //si al jugador le pertenece la propiedad
                    $pertenece2 = DB::table('propiedadjugador')
                        ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
                        ->where('partidajugador.idpartida', $objeto->idpartida)
                        ->where('propiedadjugador.idpropiedad', $elemento->id)
                        ->where('tipo', 'PROPIEDAD')
                        ->where('partidajugador.idjugador', $objeto->idjugador)
                        ->select(
                            'propiedadjugador.tipo',
                            'propiedadjugador.status'
                            ,
                            'propiedadjugador.restaurantes'
                            ,
                            'propiedadjugador.hotel'
                        )
                        ->first();
                    if (!$pertenece2) {
                        $bandera = 1;
                    }
                }
                if ($bandera == 0) {
                    //Tiene todas las propiedades
                    //SE DUPLICAN EL PRECIO DE LAS PROPIEDADES CUANDO LAS TIENES TODAS DE LA MISMA REGION vb
                    $resultado->precio = $propiedad->precio * 2;
                } else {
                    //Si por lo menos no tiene 1
                    $resultado->precio = $propiedad->precio;
                }
            }
        }
        return $resultado;
    }



    function obtener_precio_aerolinea($objeto)
    {
        $resultado = new \StdClass();
        $resultado->precio = 0;

        // Recuperar la información de la propiedad actual
        $propiedad = Propiedad::find($objeto->idpropiedad);

        // Recuperar todas las propiedades que son aerolíneas
        $propiedades_aerolineas = Propiedad::where('nombre', 'LIKE', '%Aerolínea%')->get();

        // Contar cuántas aerolíneas posee el jugador
        $aerolineas_poseidas = DB::table('propiedadjugador')
            ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
            ->where('partidajugador.idpartida', $objeto->idpartida)
            ->where('partidajugador.idjugador', $objeto->idjugador)
            ->whereIn('propiedadjugador.idpropiedad', $propiedades_aerolineas->pluck('id'))
            ->count();

        // Ajustar el precio según el número de aerolíneas poseídas
        switch ($aerolineas_poseidas) {
            case 1:
                $resultado->precio = $propiedad->precio; // Precio base
                break;
            case 2:
                $resultado->precio = $propiedad->precio * 2; // Precio se duplica
                break;
            case 3:
                $resultado->precio = $propiedad->precio * 3; // Precio se triplica
                break;
            case 4:
                $resultado->precio = $propiedad->precio * 4; // Precio se cuadruplica
                break;
            default:
                $resultado->precio = $propiedad->precio; // Caso por defecto
                break;
        }

        return $resultado;
    }


    //para bajar el consulado
    function obtenerprecioembajadaconsulado($objeto)
    {
        $resultado = new \StdClass();
        $resultado->precio = 0;

        // Recuperar la información de la propiedad actual
        $propiedad = Propiedad::find($objeto->idpropiedad);

        // Recuperar las propiedades que son Embajada o Consulado
        $eyc_propiedades = Propiedad::where('nombre', 'LIKE', '%Embajada%')
            ->orWhere('nombre', 'LIKE', '%Consulado%')
            ->pluck('id');

        // Contar cuántas de esas propiedades posee el jugador
        $propiedades_poseidas = DB::table('propiedadjugador')
            ->join('partidajugador', 'partidajugador.id', '=', 'propiedadjugador.idpartidajugador')
            ->where('partidajugador.idpartida', $objeto->idpartida)
            ->where('partidajugador.idjugador', $objeto->idjugador)
            ->whereIn('propiedadjugador.idpropiedad', $eyc_propiedades)
            ->pluck('propiedadjugador.idpropiedad')
            ->unique();
        // La función pluck se utiliza para extraer un solo valor (o una lista de valores) de una colección o de los resultados de una consulta. 
        // La función unique se utiliza para obtener elementos únicos de una colección o de un conjunto de datos. 
        // Elimina los valores duplicados y devuelve una colección con solo los valores únicos.


        // Si el jugador posee ambas propiedades (Embajada y Consulado), se duplica el precio
        if ($propiedades_poseidas->count() == 2) {
            $resultado->precio = $propiedad->precio * 2;
        } else {
            $resultado->precio = $propiedad->precio; // Precio base si solo tiene una propiedad
        }

        return $resultado;
    }



    function obtener_aerolinea($objeto)
    {
        $resultado = new \StdClass();
        $resultado->precio = 0;
        // Recuperar la información de la propiedad actual
        $propiedad = propiedad::find($objeto->idpropiedad);
        /* dd($objeto); */

        // Verificar si la propiedad existe
        if (!$propiedad) {
            $resultado->mensaje = 'La propiedad con el ID ' . $objeto->idpropiedad . ' no existe.';
            return $resultado; // Retornar con un mensaje de error
        }

        // Recuperar todas las propiedades que son aerolíneas
        $propiedades_aeropuertos = propiedad::where('nombrepro', 'LIKE', '%Aeropuerto%')->get();
        //SUEMI LAS CONSULTAS SON DIFERENTES
//     SELECT COUNT(*)
// FROM propiedadjugador
// JOIN partidajugador ON partidajugador.id = propiedadjugador.id
// WHERE partidajugador.id = idpartidajugador
//   AND partidajugador.idjugador = idjugador
//   AND propiedadjugador.idpropiedad IN (idpropiedad);
        // Contar cuántas aerolíneas posee el jugador
        $aerolineas_poseidas = DB::table('propiedadjugador')
            ->join('partidajugador', 'partidajugador.id_partidajugador', '=', 'propiedadjugador.idpartidajugador')
            ->where('partidajugador.idpartida', $objeto->idpartida)
            ->where('partidajugador.idjugador', $objeto->idjugador)
            ->whereIn('propiedadjugador.idpropiedad', $propiedades_aeropuertos->pluck('id'))
            ->count();

        // Ajustar el precio según el número de aerolíneas poseídas
        switch ($aerolineas_poseidas) {
            case 1:
                $resultado->precio = $propiedad->precio; // Precio base
                break;
            case 2:
                $resultado->precio = $propiedad->precio * 2; //x2 precio base 
                break;
            case 3:
                $resultado->precio = $propiedad->precio * 3;  //x3 precio base
                break;
            case 4:
                $resultado->precio = $propiedad->precio * 4; //x4 precio base
                break;
            default:
                $resultado->precio = $propiedad->precio;
                break;
        }

        return $resultado;
    }

    public function obtener_precio_hipoteca($objeto)
    {
        // Obtener el precio base de la propiedad
        $precio = DB::table('propiedad')
            ->where('id', $objeto->idpropiedad)
            ->value('precio') ?? 0;

        // Obtener el número de restaurantes y hoteles adquiridos
        // $restaurantesAdquiridos = DB::table('propiedadjugador')
        //     ->where('idpropiedad', $objeto->idpropiedad)
        //     ->value('restaurantes') ?? 0;

        // $hotelesAdquiridos = DB::table('propiedadjugador')
        //     ->where('idpropiedad', $objeto->idpropiedad)
        //     ->value('hotel') ?? 0;

        $restaurantesAdquiridos = DB::table('propiedadjugador')
            ->where('idpropiedad', $objeto->idpropiedad)
            ->where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->value('restaurantes') ?? 0;

        $hotelesAdquiridos = DB::table('propiedadjugador')
            ->where('idpropiedad', $objeto->idpropiedad)
            ->where('idjugador', $objeto->idjugador)
            ->where('idpartida', $objeto->idpartida)
            ->value('hotel') ?? 0;

        // Calcular hipoteca base
        $montoHipoteca = $precio;

        // Agregar costo adicional por restaurantes y hoteles
        $montoHipoteca += ($restaurantesAdquiridos * 2000); // 2000 por restaurante
        $montoHipoteca += ($hotelesAdquiridos * 3000); // 3000 por hotel

        return $montoHipoteca;
    }




}
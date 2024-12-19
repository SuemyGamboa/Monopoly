<?php
namespace App\BusinessLogic;
use Illuminate\Support\Facades\DB;
use App\Models\Partidas;
use App\Models\PartidaJugador;
use App\Models\Turno;
use App\Models\Jugadores;

use App\BusinessLogic\BoControl;
class BoTurno
{

    function obtener_turno($objeto)
    {
        return PartidaJugador::where('idpartida', $objeto->idpartida)
            ->where('idjugador', $objeto->idjugador)
            ->first();
    }

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
    function infocasilla($objeto)
    {
        $resultado = new \stdClass();
        // $resultado->tipo='Propiedad';
        $resultado->tipo = 'Salida';

        // Si es cero, ningún jugador esta en la casilla
        // Pero si tiene un valor, significa que hay un jugador en la casilla o tiene la propiedad
        // PUEDE HACER PREGUNTAS PARA SABER SI ESTA OCUPADO O NO
        $resultado->ocupado = 1;
        // $resultado->ocupado=0;
        $resultado->precio = 18000;
        $resultado->nombre = 'Inglaterra';
        return $resultado;
    }

    /////////////////////////////////////////////////EROR SE ALAMACENA LA POSICION EN TURNO, NO EN PROPIEDADPARTDA////////////////
    // El objeto tiene los siguientes atributos:
    // idjugador
    // idpartida
    function jugar($objeto)
    {
        $bocontrol = new BoControl();
        $resultado = new \stdClass();

        //1.- Validar si ya inicie mi turno, 
        $turno = $this->validar_turno_abierto($objeto);
        if ($turno) {
            // 1.1- Si ya lo inicie obtengo en que posicion me quede
            $nueva_posicion = $turno->posicion;
        } else {
            //1.2- Si no he iniciado mi turno
            // 1.-Tirar dados
            $dados = tirar_dados();

            // 2.-Mover al Jugador
            // Obtener la posición actual del jugador en una partida
            $info_turno = $this->obtener_turno($objeto);
            $nueva_posicion = $info_turno->posicion + $dados;

            //Este proceso contempla el caso en el que llego al final del tablero y me pase de la tabla
            if ($nueva_posicion > 40) {
                $nueva_posicion = $nueva_posicion - 40;
            }
            ///////////////////////////////////  (PARCHE PRINCIPAL) ////////////////////////////////////////////////////
            $nueva_posicion = 9;

            //posicion en el tablero
            //iniciar mi turno
            ///////////CODIGO ORIGINAL PARA RANDOM 
              $objeto->posicion=$nueva_posicion;
               $this->crear($objeto);

            //////////////////////////////////////////////////////////////////////////////////////////////////// 
        }



        // 3.-Obtener la información de la casilla
        $objeto2 = new \stdClass();
        $objeto2->posicion = $nueva_posicion;
        $objeto2->idjugador = $objeto->idjugador;
        $objeto2->idpartida = $objeto->idpartida;
        // $res1 = $this->info_casilla($objeto2);
        $res1 = $bocontrol->info_casilla($objeto2);
        return $res1;
    }



    function crear($objeto)
    {
        //1.- Recuperar con la partida y el jugador el idpartidaxjugador
        $parxjug = $this->obtener_turno($objeto);

        //2.- Insertar en la tabla turno el registro
        $turno = new Turno();
        //0 significa que no ha terminado mi turno, 1 significa que ya terminó
        $turno->status = 0;
        $turno->idpartidajugador = $parxjug->id;
        $turno->posicion = $objeto->posicion;
        $turno->save();
        return $turno;
    }


    //desglozar falta posicion//////////////////////////////////////////////////////////////////////
    function validar_turno_abierto($objeto)
    {
        $turno = DB::table('partidajugador')
            ->join('turno', 'turno.idpartidajugador', '=', 'partidajugador.id')
            ->where('partidajugador.idpartida', $objeto->idpartida)
            ->where('partidajugador.idjugador', $objeto->idjugador)
            ->where('turno.status', 0)
            ->first();
        if ($turno)
            return $turno;
        else
            return false;
    }

    function cerrar($objeto)
    {
        //1.- Modificar el status del turno
        $parxjug = $this->obtener_turno($objeto);
        $turno = Turno::where('idpartidajugador', $parxjug->id)
            ->where('status', 0)->first();
        $turno->status = 1;
        $turno->save();

        //2.- Aumentar el turno de la partida para indicar al siguiente jugador
        $turno_nuevo = $parxjug->turno + 1;
        //2.1.- Obtener el número de jugadores de la partida
        $jugadores = PartidaJugador::where('idpartida', $objeto->idpartida)
            ->get();
        //2.2.- Verificar si el número que obtenemos es mayor al número de jugadores
        if ($turno_nuevo > count($jugadores)) {
            //2.3 Si es así el turno se reinicia
            $turno_nuevo = 1;
        }

        //3.- Guardar en la partida el siguiente turno
        $partida = Partidas::find($objeto->idpartida);
        $partida->turno = $turno_nuevo;
        $partida->save();
    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Incluyo el modelo para poder ser utilizado en el controlador
use App\Models\Jugadores;
use App\Models\Usuarios;
use App\Models\Roles;
use App\BusinessLogic\BoTurno;
use App\BusinessLogic\BoPartida;
use App\BusinessLogic\BoControl;

// Esta clase sirve para manejar los datos de la sesión del animal
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class JugadorController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        $datos['registros'] = Jugadores::all();
        return view('jugador.listado')->with($datos);
    }

    function formulario($id = 0){
        $datos = array();
        if($id == 0){
            // Agregar
            $datos['jugador'] = new Jugadores();
            $datos['operacion'] = 'Agregar';
            $datos['jugador'] -> id = 0;
        } else {
            // Editar
            $datos['jugador'] = Jugadores::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('jugador.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request){
        // 1.-Recupero toda la información de la petición
        //$context = $r->all();
        $context = $request->all();
        $archivo = $request -> file('foto');
        switch($context['operacion']){
            case 'Agregar':      
                $jugador = new Jugadores();
                $jugador->nombre = $context['nombre'];
                $jugador->edad = $context['edad'];
                $jugador->foto = '';
                $jugador->save();

                if($request->hasFile('foto')){
                    $nombre_archivo='jugador-'.$jugador->id.'.'.$archivo->getClientOriginalExtension();
                    $archivo->storeAs('imagenes/jugador',$nombre_archivo);
                    $jugador->foto=$nombre_archivo;
                    $jugador->save();
                }
                break;

            case 'Modificar':
                $jugador = Jugadores::find($context['id']);
                $jugador->nombre = $context['nombre'];
                $jugador->edad = $context['edad'];

                if($request->hasFile('foto')){
                    //Elimna el archivo anterior pero valida antes si había una foto previa
                    if($jugador->foto!=''){
                        Storage::delete('imagenes/jugador/'.$jugador->foto);
                    }
                    
                    $nombre_archivo='jugador-'.$jugador->id.'.'.$archivo->getClientOriginalExtension();

                    $archivo->storeAs('imagenes/jugador',$nombre_archivo);

                    $jugador->foto=$nombre_archivo;
                }
                $jugador->save();
            
            break;

            case 'Eliminar':
               $jugador = Jugadores::find($context['id']);

                if($jugador->foto!=''){
                    Storage::delete('imagenes/jugador/'.$jugador->foto);
                }
            
                $jugador->delete();
                break;
            }
            return redirect()->route('index_jugador');
    }
    
        public function mostrar_foto($nombre_foto){
            
            $path = storage_path('app/imagenes/jugador/'.$nombre_foto);
            if (!File::exists($path)){
                abort(404);
            }
            
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
    }

    function registrate(){
        $datos = array();
        return view ('app.register')->with($datos);
    }

//Permitir a los usuarios registrarse como Jugadores
// function autoregistro(Request $request){
//     $context = $request->all();

//     // 1.- Insertar un Usuario
//     $user = new Usuarios();
//     $user->email = $context['email'];
//     // bcrypt: para encriptar la contraseña
//     if($context['password'] != ''){
//         // dd('Se Cambio la contraseña');
//         $user->password = bcrypt($context['password']);
//     }

//     //PREGUNTA DE ORDINARIO
//     $user->idrol = 2;
//     $user->save();

//     // 2.- Insertar un Jugador
//     $player = new Jugadores();
//     $player->nombre = $context['nombre'];
//     $player->edad = $context['edad'];
//     // $player->foto = '';
//     //Establece la relación entre la tabla personaje y la tabla user
//     $player->idusuario = $user->id;
//     $player->save();

//     // 3.- Hacer el login
//     //LOGIN AUTOMATICO
//     Auth::loginUsingId($user->id);

//     // 4.- Redirigir a la página de bienvenida
//     return redirect()->route('home_jugador');
    
// }


public function autoregistro(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:usuarios,email',
        'password' => 'required|min:8',
        'nombre' => 'required|string|max:255',
        'edad' => 'required|integer|min:1',
    ]);

    // 1.- Crear el usuario
    $usuario = new Usuarios();
    $usuario->email = $request->email;
    $usuario->password = bcrypt($request->password);
    $usuario->idrol = 2; // Rol por defecto
    $usuario->save();

    // 2.- Crear el jugador asociado
    $jugador = new Jugadores();
    $jugador->nombre = $request->nombre;
    $jugador->edad = $request->edad;
    $jugador->idusuario = $usuario->id; // Relación con el usuario
    $jugador->save();

    // 3.- Login automático
    Auth::loginUsingId($usuario->id);

    // 4.- Redirigir
    return redirect()->route('home_jugador')->with('success', 'Registro exitoso.');
}




    function home(){
        //-Recupero al usuario
        $usuario=Auth::user();
        //jugador relacionado al usuario
        $jugador=Jugadores::where('idusuario', $usuario->id)->first();
        $nombre=$jugador->nombre;

        //Nuevo Codigo
        $bo=new BoPartida();
        $objeto=new \stdClass();
        $objeto->idjugador=$jugador->id;
        //

        //Recuperar de la sesion al USUARIO
        $datos=array();
        $datos ['nombre']=$nombre;
        //Nuevo Codigo
        $datos ['partidas']=$bo->listar_partidas_jugador($objeto);
        return view('home.jugador')->with($datos);
       
    }
}
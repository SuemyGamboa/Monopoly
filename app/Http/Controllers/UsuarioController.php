<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Incluyo el modelo para poder ser utilizado en el controlador
use App\Models\Roles;
use App\Models\Usuarios;
// Esta clase sirve para manejar los datos de la sesión del animal
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class UsuarioController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        // $datos['registros'] = Usuarios::all();
        $datos['registros'] = Usuarios::join('rol', 'rol.id', '=', 'usuario.idrol')
        ->select(
            "usuario.email",
            "usuario.id",
            "usuario.idrol",
            "rol.nombre as idrol"
        )
        ->get();
        return view('usuario.listado')->with($datos);
    } 

    function formulario($id = 0){
        $datos = array();
        $datos['rol'] = Roles::all();
        if($id == 0){
            // Agregar
            $datos['usuario'] = new Usuarios();
            $datos['operacion'] = 'Agregar';
            $datos['usuario'] -> id = 0;
        } else {
            // Editar
            $datos['usuario'] = Usuarios::find($id);
            $datos['operacion'] = 'Modificar';
        }
        return view('usuario.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request){
        // 1.-Recupero toda la información de la petición
        //$context = $r->all();
        $context = $request->all();
       
        switch($context['operacion']){
            case 'Agregar':      
                $usuario = new Usuarios();
                $usuario->email = $context['email'];
                if($context['password'] != ''){
                    $usuario->password = bcrypt($context['password']);
                }                
                $usuario->idrol = $context['idrol'];
                $usuario->save();
                break;

            case 'Modificar':
                $usuario = Usuarios::find($context['id']);
                $usuario->email = $context['email'];
                if($context['password'] != ''){
                    $usuario->password = bcrypt($context['password']);
                }                
                $usuario->idrol = $context['idrol'];
                $usuario->save();
            break;

            case 'Eliminar':
               $usuario = Usuarios::find($context['id']);
                $usuario->delete();
                break;
            }
            return redirect()->route('index_usuario');
    }


    function home(){
        return view('home.administrador');       
    }
}

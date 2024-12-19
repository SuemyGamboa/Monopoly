<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Incluyo el modelo para poder ser utilizado en el controlador
use App\Models\Roles;
// Esta clase sirve para manejar los datos de la sesión del animal
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class RolController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        $datos['registros'] = Roles::all();
        return view('rol.listado')->with($datos);
    }

    function formulario($id = 0){
        $datos = array();
        if($id == 0){
            // Agregar
            $datos['rol'] = new Roles();
            $datos['operacion'] = 'Agregar';
            $datos['rol'] -> id = 0;
        } else {
            // Editar
            $datos['rol'] = Roles::find($id);
            $datos['operacion'] = 'Modificar';
        }
        return view('rol.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request){
        // 1.-Recupero toda la información de la petición
        //$context = $r->all();
        $context = $request->all();
       
        switch($context['operacion']){
            case 'Agregar':      
                $rol = new Roles();
                $rol->nombre = $context['nombre'];
                $rol->save();
                break;

            case 'Modificar':
                $rol = Roles::find($context['id']);
                $rol->nombre = $context['nombre'];

                $rol->save();
            break;

            case 'Eliminar':
               $rol = Roles::find($context['id']);
                $rol->delete();
                break;
            }
            return redirect()->route('index_rol');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Incluyo el modelo para poder ser utilizado en el controlador
use App\Models\Permisos;

// Esta clase sirve para manejar los datos de la sesión del animal
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class PermisoController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        $datos['registros'] = Permisos::all();
        return view('permisos.listado')->with($datos);
    }

    function formulario($id = 0){
        $datos = array();
        if($id == 0){
            // Agregar
            $datos['permiso'] = new Permisos();
            $datos['operacion'] = 'Agregar';
            $datos['permiso'] -> id = 0;
        } else {
            // Editar
            $datos['permiso'] = Permisos::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('permisos.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request){
        // 1.-Recupero toda la información de la petición
        //$context = $r->all();
        $context = $request->all();
        $archivo = $request -> file('foto');
        switch($context['operacion']){
            case 'Agregar':      
                $permisos = new Permisos();
                $permisos->nombre = $context['nombre'];
                $permisos->clave = $context['clave'];
                $permisos->save();

                break;

            case 'Modificar':
                $permisos = Permisos::find($context['id']);
                $permisos->nombre = $context['nombre'];
                $permisos->clave = $context['clave'];

                $permisos->save();
            
            break;

            case 'Eliminar':
               $permisos = Permisos::find($context['id']);
                $permisos->delete();
                break;
            }
        
            return redirect()->route('index_permisos');
    }
}
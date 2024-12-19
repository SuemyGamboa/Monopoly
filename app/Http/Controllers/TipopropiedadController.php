<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipopropiedad;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class TipopropiedadController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        $datos['registros'] = Tipopropiedad::all();
        return view('tipopropiedad.listado')->with($datos);
    }

    function formulario($id = 0){
        $datos = array();
        if($id == 0){
            // Agregar
            $datos['tipopropiedad'] = new Tipopropiedad();
            $datos['operacion'] = 'Agregar';
            $datos['tipopropiedad'] -> id = 0;
        } else {
            // Editar
            $datos['tipopropiedad'] = Tipopropiedad::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('tipopropiedad.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request){
        // 1.-Recupero toda la información de la petición
        //$context = $r->all();
        $context = $request->all();
        switch($context['operacion']){
            case 'Agregar':      
                $tipopropiedad = new Tipopropiedad();
                $tipopropiedad->nombre = $context['nombre'];
                $tipopropiedad->save();
            break;

            case 'Modificar':
                $tipopropiedad = Tipopropiedad::find($context['id']);
                $tipopropiedad->nombre = $context['nombre'];
                $tipopropiedad->save();
                
            break;

            case 'Eliminar':
               $tipopropiedad = Tipopropiedad::find($context['id']);
               
                $tipopropiedad->delete();
                break;
            }
            return redirect()->route('index_tipopropiedad');
    }

}
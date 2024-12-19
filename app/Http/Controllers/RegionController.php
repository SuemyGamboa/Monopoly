<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Incluyo el modelo para poder ser utilizado en el controlador
use App\Models\Regiones;
// Esta clase sirve para manejar los datos de la sesión del animal
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class RegionController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        $datos['registros'] = Regiones::all();
        return view('region.listado')->with($datos);
    }

    function formulario($id = 0){
        $datos = array();
        if($id == 0){
            // Agregar
            $datos['region'] = new Regiones();
            $datos['operacion'] = 'Agregar';
            $datos['region'] -> id = 0;
        } else {
            // Editar
            $datos['region'] = Regiones::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('region.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request){
        // 1.-Recupero toda la información de la petición
        //$context = $r->all();
        $context = $request->all();
        $archivo = $request -> file('foto');
        switch($context['operacion']){
            case 'Agregar':      
                $region = new Regiones();
                $region-> nombre = $context['nombre'];
                $region-> color = $context['color'];
                $region-> foto = '';
                $region->save();

                if($request->hasFile('foto')){
                    $nombre_archivo='region-'.$region->id.'.'.$archivo->getClientOriginalExtension();
                    $archivo->storeAs('imagenes/region',$nombre_archivo);
                    $region->foto=$nombre_archivo;
                    $region->save();
                }
                break;

            case 'Modificar':
                $region = Regiones::find($context['id']);
                $region->nombre = $context['nombre'];
                $region-> color = $context['color'];


                if($request->hasFile('foto')){
                    //Elimna el archivo anterior pero valida antes si había una foto previa
                    if($region->foto!=''){
                        Storage::delete('imagenes/region/'.$region->foto);
                    }
                    
                    $nombre_archivo='region-'.$region->id.'.'.$archivo->getClientOriginalExtension();

                    $archivo->storeAs('imagenes/region',$nombre_archivo);

                    $region->foto=$nombre_archivo;
                }
                $region->save();
            
            break;

            case 'Eliminar':
               $region = Regiones::find($context['id']);

                if($region->foto!=''){
                    Storage::delete('imagenes/region/'.$region->foto);
                }
            
                $region->delete();
                break;
            }
            return redirect()->route('index_region');
    }
        public function mostrar_foto($nombre_foto){
            
            $path = storage_path('app/imagenes/region/'.$nombre_foto);
            if (!File::exists($path)){
                abort(404);
            }
            
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }

}
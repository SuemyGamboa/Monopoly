<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Propiedad;
use App\Models\Tipopropiedad;
use App\Models\Regiones;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\DB;

class PropiedadController extends Controller
{
    function index(){
        // Listar todos los registros de los alumnos
        $datos = array();
        //$datos['registros'] = Propiedades::all();
        
        $datos['registros'] = DB::table('propiedad')
        //El join es una consulta que se usa para relacionar dos o más tablas
        ->join('tipopropiedad', 'propiedad.idtipopropiedad', '=', 'tipopropiedad.id')
        ->join('region', 'propiedad.idregion', '=', 'region.id')
        //Busca los nombres de las tablas y los muestra bajo un alias
        ->select('propiedad.*', 'tipopropiedad.nombre as tipopropie', 'region.nombre as regioname')
        //GET: Se utiliza para recuperar información
        ->get();

        return view('propiedad.listado')->with($datos);
    }

    function formulario($id = 0){
        $datos = array();
        if($id == 0){
            // Agregar
            $datos['propiedad'] = new Propiedad();
            $datos['operacion'] = 'Agregar';
            $datos['propiedad'] -> id = 0;
        } else {
            // Editar
            $datos['propiedad'] = Propiedad::find($id);
            $datos['operacion'] = 'Modificar';
        }

        $datos['tipo_propiedad'] = Tipopropiedad::all();
        $datos['region'] = Regiones::all();
        return view('propiedad.formulario')->with($datos);
    }

    //function save(Request $r){
    function save(Request $request){
        // 1.-Recupero toda la información de la petición
        //$context = $r->all();
        $context = $request->all();
        $archivo = $request -> file('foto');
        switch($context['operacion']){
                case 'Agregar':
                    $propiedad = new Propiedad();
                    $propiedad->nombre = $context['nombre'];
                    $propiedad->precio = $context['precio'];
                    $propiedad->idtipopropiedad = $context['idtipopropiedad'];
                    $propiedad->idregion = $context['idregion'];
                    $propiedad->orden = $context['orden'];
                    $propiedad->foto = '';
                    $propiedad->save();
    
                    if($request->hasFile('foto')){
                        $nombre_archivo='propiedad-'.$propiedad->id.'.'.$archivo->getClientOriginalExtension();
                        $archivo->storeAs('fotos/propiedad',$nombre_archivo);
    
                        $propiedad->foto=$nombre_archivo;
                        $propiedad->save();
                    }
                break;

            case 'Modificar':
                $propiedad = Propiedad::find($context['id']);
                $propiedad->nombre = $context['nombre'];

                if($request->hasFile('foto')){
                    //Elimna el archivo anterior pero valida antes si había una foto previa
                    if($propiedad->foto!=''){
                        Storage::delete('fotos/propiedad/'.$propiedad->foto);
                    }
                    
                    $nombre_archivo='propiedad-'.$propiedad->id.'.'.$archivo->getClientOriginalExtension();

                    $archivo->storeAs('fotos/propiedad',$nombre_archivo);

                    $propiedad->foto=$nombre_archivo;
                }
                $propiedad->save();
            
            break;

            case 'Eliminar':
               $propiedad = Propiedad::find($context['id']);

                if($propiedad->foto!=''){
                    Storage::delete('fotos/propiedad/'.$propiedad->foto);
                }
            
                $propiedad->delete();
                break;
            }
            return redirect()->route('index_propiedad');
    }
        public function mostrar_foto($nombre_foto){
            
            $path = storage_path('app/fotos/propiedad/'.$nombre_foto);
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
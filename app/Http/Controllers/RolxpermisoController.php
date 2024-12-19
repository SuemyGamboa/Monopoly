<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Incluyo el modelo para poder ser utilizado en el controlador
use App\Models\Permisos;
use App\Models\RolPermisos;

// Esta clase sirve para manejar los datos de la sesión del animal
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class RolxpermisoController extends Controller
{
    function formulario($id){
        $datos=array();
        $datos['id']=$id;
       
        // Mantiene seleccionado las casillas que se están usando y desactiva las que no
        $temp=Permisos::all();
        for($i=0;$i<count($temp);$i++){
            $validacion=RolPermisos::where('idrol',$id)->where('idpermiso',$temp[$i]->id)->first();
            if($validacion)
                $temp[$i]->asignado=true;
            else
                $temp[$i]->asignado=false;
        }
    
        // $datos['permisos']=Permiso::all();
        $datos['per']=$temp;
        return view('rolxpermisos.formulario')->with($datos);
    
       }

       function save(Request $request){
        $context=$request->all();
    
        // Borra todos los permisos del rol que estaban asignados previamente
        RolPermisos::where('idrol',$context['idrol'])->delete();
    
        // Pregunta antes si el usuario selecciono un permiso
        if (isset($context['idpermiso'])){
            // Asigna los permisos que viene del formulario
            foreach($context['idpermiso'] as $idpermiso){
                // Le asigna a cada rol sus permisos correspondientes
                $rxp=new RolPermisos();
                $rxp->idrol=$context['idrol'];
                $rxp->idpermiso=$idpermiso;
                $rxp->save();
            }
        }
        return redirect()->route('index_rol');
    }
}
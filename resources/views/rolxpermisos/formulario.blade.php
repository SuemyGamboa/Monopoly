
@extends('app.master')

@section('titulo')
Gestión de rol permiso
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Catalogo de rol permiso</h2>
      <form action="{{ action([App\Http\Controllers\RolxpermisoController::class, 'save']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" name="idrol" value="{{$id}}" />
                <table class="table table-bordered">
                    @foreach($per as $dato)
                <tr>
                <td>
                <?php
                $flor='';
                if($dato->asignado==true)
                  $flor=' checked ';
                ?>
                <input {{$flor}} type="checkbox" name="idpermiso[]" value="{{$dato->id}}">
                </td>
                <td>{{$dato->nombre}}</td>
                <td>{{$dato->clave}}</td>
                </tr>
                @endforeach
                </table>
            
            <input name="operacion" class="btn btn-success" value=" Añadir permiso " type="submit">
            
        </form>
    </div>
</div>
@endsection
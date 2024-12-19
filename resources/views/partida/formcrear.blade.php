
@extends('app.master')

@section('titulo')
Formulario de partidas
@endsection

@section('contenido')


<body>

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Crear una nueva partida</h2>
        <!--Necesita de esto para las imagenes: enctype="multipart/form-data"-->
        <form action="{{ action([App\Http\Controllers\PartidaController:: class, 'crear']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
           
            <div class="form-group">
                <label for="nombre "clave>Nombre</label>
                <input type="text" class="form-control" name="nombre" value="">
            </div>
            <div class="form-group">
                <label for="fecha">Clave</label>
                <input type="text" class="form-control" name="clave" value="" >
            </div>
            <input name="operacion" class="btn btn-success" value="crear partida" type="submit">
          
        </form>
    </div>
</div>
@endsection
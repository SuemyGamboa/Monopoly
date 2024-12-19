
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
            <h2>Agregar Partida</h2>

            <form action="{{ action([App\Http\Controllers\PartidaController::class, 'save']) }}" method="POST"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $partida->id }}">

                <div class="form-group">
                    <label for="">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                        value="" required>
                </div>
                <div class="form-group">
                    <label for="" clave>Clave</label>
                    <input type="text" class="form-control"id="clave" name="clave" value="{{ $partida->clave }}">
                </div>
             
                <input name="operacion" class="btn btn-success" value="{{ $operacion }}" type="submit">
                <!-- @if($operacion == 'Modificar')
                    <input name="operacion" class="btn btn-danger" value="Eliminar" type="submit">
                @endif -->

                   
            <a href="{{ route('index_partida') }}" class="btn btn-secondary">Cancelar</a>
      
            </form>
        </div>
    </div>
    @endsection
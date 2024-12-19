@extends('app.master')

@section('titulo')
Gestión de partidas
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
        <br>
        <br>
        <h2>Catálogo de Partidas</h2>
        <br>
        <a class="btn btn-red" href="{{ action([App\Http\Controllers\PartidaController::class, 'formulario']) }}">
            Agregar nueva Partida
        </a>
        <br>
        <br>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Clave</th>
                    <th>Opciones</th>
                </tr>
            </thead>


            <tbody>
                @foreach($registros as $registro)
                    <tr>
                        <td> {{ $registro->id }}</td>


                        <td>{{ $registro->nombre }}</td>

                        <td>  {{ $registro->clave }}
                            
                        </td>


                        <td>
                            <a class="btn btn-primary"
                                href="{{ action([App\Http\Controllers\PartidaController::class, 'formulario'], ['id' => $registro->id]) }}">Editar</a>
                            <form action="{{ action([App\Http\Controllers\PartidaController::class, 'save']) }}"
                            method="POST"style="display:inline;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $registro->id }}">
                                <input name="operacion" class="btn btn-danger"
                                    onclick=" return confirm('¿Estás seguro de eliminar este rol?')" value="Eliminar"
                                    type="submit">

                            </form>
                        </td>

                      
                    </tr>
                @endforeach
            </tbody>
        </table>




    </div>
</div>
@endsection
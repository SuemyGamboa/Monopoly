@extends('app.master')

@section('titulo')
Gestión de Jugadores
@endsection

@section('contenido')
<div class="container">
    <div class="col-md-12">
        <br>
        <br>
        <h2>Catálogo de Jugadores</h2>
        <br>
        <a class="btn btn-red" href="{{ action([App\Http\Controllers\JugadorController::class, 'formulario']) }}">
            Agregar nuevo Jugador
        </a>
        <br>
        <br>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Foto</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $registro)
                    <tr>
                        <td> {{ $registro->id }}</td>
                        <td>
                             {{ $registro->nombre }}
                            
                        </td>
                        <td>{{ $registro->edad }}</td>
                        <td>
                            @if($registro->foto)
                                <img class="img-thumbnail"
                                    src="{{ action([App\Http\Controllers\JugadorController::class, 'mostrar_foto'], ['nombre_foto' => $registro->foto]) }}"
                                    width="100px" height="100px">
                            @else
                                <p>Sin foto</p>
                            @endif
                        </td>

                        <td>
                            <a class="btn btn-primary"
                                href="{{ action([App\Http\Controllers\JugadorController::class, 'formulario'], ['id' => $registro->id]) }}">Editar</a>
                            <form action="{{ action([App\Http\Controllers\JugadorController::class, 'save']) }}"
                                method="POST" style="display:inline;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $registro->id }}">
                                <input name="operacion" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este rol?')" value="Eliminar"
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
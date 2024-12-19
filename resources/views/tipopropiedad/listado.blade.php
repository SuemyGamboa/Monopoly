@extends('app.master')

@section('titulo')
Gestión de tipo propiedad
@endsection

@section('contenido')


<div class="container">
    <h2 class="mt-4">Catálogo de Tipos de propiedades</h2>
    <br>
    <a class="btn btn-red" href="{{ action([App\Http\Controllers\TipopropiedadController::class, 'formulario']) }}">
        Añadir Tipo de propiedad
    </a>
    <br>
    <br>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $registro)
                <tr>
                    <td> {{ $registro->id }}</td>
                    <td>   {{ $registro->nombre }}
                    </td>
                    <td>
                    <a class="btn btn-primary"
                            href="{{ action([App\Http\Controllers\PropiedadjugadorController::class, 'formulario'], ['id' => $registro->id]) }}"> Editar
                            </a>
                        <form action="{{ action([App\Http\Controllers\TipopropiedadController::class, 'save']) }}"
                            method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $registro->id }}">
                            <input type="hidden" name="operacion" value="Eliminar">
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
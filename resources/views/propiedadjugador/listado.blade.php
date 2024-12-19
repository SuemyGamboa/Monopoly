
@extends('app.master')

@section('titulo')
Gestionar propiedades del jugador
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
        <h2>Catálogo de Propiedades del jugador</h2>
        <a class="btn btn-red" href="{{ action([App\Http\Controllers\PropiedadjugadorController:: class, 'formulario']) }}">
            Añadir Propiedad del jugador
        </a>
        <br>
        <br>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Propiedad</th>
                <th>Jugador</th>
                <th>Partida</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($registros as $registro)
            <tr>
            <td> {{ $registro->id }}</td>
            <td><a href="{{ action([App\Http\Controllers\PropiedadjugadorController:: class, 'formulario'],['id'=>$registro->id]) }}">
            {{ $registro->propiedad_nombre }}
            </a>
            </td>
            <td> {{ $registro->jugador_nombre }}</td>
            <td> {{ $registro->partida_clave }}</td>
        <td>
            <form action="{{ action([App\Http\Controllers\PropiedadjugadorController:: class, 'save']) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $registro->id }}">
                <input type="hidden" name="operacion" value="Eliminar">
                <button type="submit" class="btn btn-danger btn-sm">
                    Eliminar
                </button>
            </form>
        </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
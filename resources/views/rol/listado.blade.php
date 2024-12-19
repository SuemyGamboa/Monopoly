
@extends('app.master')

@section('titulo')
Listado de Roles
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Catálogo de Roles</h2>
        <br>
        <a class="btn btn-red" href="{{ action([App\Http\Controllers\RolController:: class, 'formulario']) }}">
            Añadir Rol
        </a>
        <br>
        <br>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Permisos</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($registros as $registro)
            <tr>
                <td> {{ $registro->id }}</td>
                <td>
                    <a href="{{ action([App\Http\Controllers\RolController:: class, 'formulario'],['id'=>$registro->id]) }}">
                        {{ $registro->nombre }}
                    </a>
            </td>
            <td>
                <a href="{{ action([App\Http\Controllers\RolxpermisoController:: class, 'formulario'], ['id' => $registro->id]) }}">
                    Permisos
                </a>
            </td>
            <td>
                <form action="{{ action([App\Http\Controllers\RolController:: class, 'save']) }}" method="POST">
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
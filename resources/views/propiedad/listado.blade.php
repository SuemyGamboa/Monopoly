
@extends('app.master')

@section('titulo')
Listado de propiedades
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Catálogo de Propiedades</h2>
        <br>
        <a class="btn btn-red" href="{{ action([App\Http\Controllers\PropiedadController:: class, 'formulario']) }}">
            Añadir Propiedad
        </a>
        <br>
        <br>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Tipo de propiedad</th>
                <th>Region</th>
                <th>Orden</th>
                <th>Foto</th>
                <th></th> 
                
                <!-- <th>Id</th>
                <th>Nombre</th>
                <th>Foto</th>
                <th>Region</th>
                <th>Precio</th>
                <th>Renta</th>
                <th>R1</th>
                <th>R2</th>
                <th>R3</th> 
                <th></th> 
                <th>Hotel</th> 
                <th>Restaurant</th> 
                <th>Hipoteca</th>   -->
            </tr>
            </thead>
            <tbody>
            @foreach($registros as $registro)
        <tr>
                <td> {{ $registro->id }}</td>
                <td>
                <a href="{{ action([App\Http\Controllers\PropiedadController:: class, 'formulario'],['id'=>$registro->id]) }}">
                {{ $registro->nombre }}
                
                
            </a>
        </td>
                <td>{{ $registro->precio }}</td>
                <td>{{ $registro->tipopropie }}</td>
                <td>{{ $registro->regioname }}</td>
                <td>{{ $registro->orden }}</td>
            <td>
                @if($registro->foto)
                    <img width="50" height="50" class="img-thumbnail" src="{{ action([App\Http\Controllers\PropiedadController:: class, 'mostrar_foto'], ['nombre_foto' => $registro->foto]) }}">
                @else
                    <p>No hay imagen</p>
                @endif
            </td>

        <td>
            <form action="{{ action([App\Http\Controllers\PropiedadController:: class, 'save']) }}" method="POST">
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
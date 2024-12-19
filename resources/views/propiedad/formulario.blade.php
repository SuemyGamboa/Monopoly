<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
    <link href="{{asset('css/cute.css')}}" rel="stylesheet" />

    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>
    <title>Propiedades</title>
    Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--<link rel="stylesheet" href="{{ asset('public/bootstrap.css') }}">-->
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>-->
@extends('app.master')

@section('titulo')
Formulario de Propiedades
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Formulario de Propiedades</h2>
        <!--Necesita de esto para las imagenes: enctype="multipart/form-data"-->
        <form action="{{ action([App\Http\Controllers\PropiedadController:: class, 'save']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $propiedad->id }}">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="{{ $propiedad->nombre }}">
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="text" class="form-control" name="precio" value="{{ $propiedad->precio }}">
            </div>
            

            <div class="form-group">
                <label for="idtipopropiedad">Tipo de propiedad</label>
                <select class="form-control" name="idtipopropiedad">
                            @foreach($tipo_propiedad as $datos)
                                <option value="{{ $datos->id }}" {{ $propiedad->idtipopropiedad == $datos->id ? 'selected' : '' }}>
                                    {{ $datos->nombre }}
                                </option>
                            @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="idregion">Region</label>
                <select class="form-control" name="idregion">
                            @foreach($region as $datos)
                                <option value="{{ $datos->id }}" {{ $propiedad->idregion == $datos->id ? 'selected' : '' }}>
                                    {{ $datos->nombre }}
                                </option>
                            @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="orden">Orden</label>
                <input type="text" class="form-control" name="orden" value="{{ $propiedad->orden }}">
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" class="form-control" name="foto" value="">
            </div>
            <input name="operacion" class="btn btn-success" value="{{ $operacion }}" type="submit">
            @if($operacion == 'Modificar')
                <input name="operacion" class="btn btn-danger" value="Eliminar" type="submit">
            @endif
        </form>
    </div>
</div>

@endsection
<!--<!DOCTYPE html>
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
    <title>Propiedad jugador</title>
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
Formulario de propiedad x jugador
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
        <h2>Formulario de Propiedad jugador</h2>
        <!--Necesita de esto para las imagenes: enctype="multipart/form-data"-->
        <form action="{{ action([App\Http\Controllers\PropiedadxjugadorController:: class, 'save']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $propiedadxjugador->id }}">
            <div class="form-group">
                <label for="idpropiedad">Propiedad</label>
                <select class="form-control" name="idpropiedad">
                            @foreach($propiedad as $datos)
                                <option value="{{ $datos->id }}" {{ $propiedadxjugador->idpropiedad == $datos->id ? 'selected' : '' }}>
                                    {{ $datos->nombre }}
                                </option>
                            @endforeach
                </select>
            </div>

            
            <div class="form-group">
                <label for="idjugador">Jugador</label>
                <select class="form-control" name="idjugador">
                            @foreach($jugador as $datos)
                                <option value="{{ $datos->id }}" {{ $propiedadxjugador->idjugador == $datos->id ? 'selected' : '' }}>
                                    {{ $datos->nombre }}
                                </option>
                            @endforeach
                </select>
            </div>

            
            <div class="form-group">
                <label for="idpartida">Partida</label>
                <select class="form-control" name="idpartida">
                            @foreach($partida as $datos)
                                <option value="{{ $datos->id }}" {{ $propiedadxjugador->idpartida == $datos->id ? 'selected' : '' }}>
                                    {{ $datos->clave }}
                                </option>
                            @endforeach
                </select>
            </div>

            <input name="operacion" class="btn btn-success" value="{{ $operacion }}" type="submit">
            @if($operacion == 'Modificar')
                <input name="operacion" class="btn btn-danger" value="Eliminar" type="submit">
            @endif
        </form>
    </div>
</div>
@endsection
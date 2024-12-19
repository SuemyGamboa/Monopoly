<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
    <link href="{{asset('css/cute.css')}}" rel="stylesheet" />
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>
    <title>Permisos</title>
     Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.6/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--<link rel="stylesheet" href="{{ asset('public/bootstrap.css') }}">
</head>-->
@extends('app.master')

@section('titulo')
Listado de permisos
@endsection

@section('contenido')


<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Catálogo de Permisos</h2>
        <br>
        <a class="btn btn-red" href="{{ action([App\Http\Controllers\PermisoController:: class, 'formulario']) }}">
            Añadir Permiso
        </a>
        <br>
        <br>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>   
                <th>Clave</th>            
                <th> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($registros as $registro)
            <tr>
                <td> {{ $registro->id }}</td>
            <td>
                <a href="{{ action([App\Http\Controllers\PermisoController:: class, 'formulario'],['id'=>$registro->id]) }}">
                    {{ $registro->nombre }}
                </a>
            </td>
            
            <td>{{ $registro->clave }}</td>

            <td>
                <form action="{{ action([App\Http\Controllers\PermisoController:: class, 'save']) }}" method="POST">
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
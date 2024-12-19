
@extends('app.master')

@section('titulo')
aduana
@endsection

@section('contenido')


<body>

<div class="container">
    <div class="col-md-12">
    <div class="card-body">
                        Caiste en Aduana<br>
                        Te descontaron el 10% de tu dinero
                        @if($casilla->perdio==1)
                        Ya perdiste 
                        @endif
                    </div>
    </div>
</div>
@endsection
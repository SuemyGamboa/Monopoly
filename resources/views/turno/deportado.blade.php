
@extends('app.master')

@section('titulo')
Carta
@endsection

@section('contenido')


<body>

<div class="container">
    <div class="col-md-12">
    <div class="card-body">
                        Deportado<br>
                        perdiste 
                        @if($casilla->perdio==1)
                        Ya perdiste 
                        @endif
                    </div>
    </div>
</div>
@endsection
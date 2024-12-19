
@extends('app.master')

@section('titulo')
Visa
@endsection

@section('contenido')


<body>

<div class="container">
    <div class="col-md-12">
    <div class="card-body">
                        Caiste en Visa<br>
                        te descontaron 10mil
                        @if($casilla->perdio==1)
                        Ya perdiste 
                        @endif
                    </div>
    </div>
</div>
@endsection
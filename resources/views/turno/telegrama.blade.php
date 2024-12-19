
@extends('app.master')

@section('titulo')
Telegrama
@endsection

@section('contenido')


<body>

<div class="container">
    <div class="col-md-12">
    <div class="card-body">
                        Caiste en una Telegrama<br>
                        {{$casilla->mensaje}}<br>
                        @if (isset($casilla->perdio))
                            @if($casilla->perdio==1)
                                Ya perdiste 
                            @endif
                        @endif
                    </div>
    </div>
</div>
@endsection
{{-- 
@extends('app.master')

@section('titulo')
Carta
@endsection

@section('contenido')


<body>

<div class="container">
    <div class="col-md-12">
    <div class="card-body">
                        Caiste en una Carta<br>
                        {{$casilla->mensaje}}<br>
                        @if (isset($casilla->perdio))
                            @if($casilla->perdio==1)
                                Ya perdiste 
                            @endif
                        @endif
                    </div>
    </div>
</div>
@endsection --}}

@extends('app.master')

@section('titulo')
Carta
@endsection

@section('contenido')
<div class="container">
    <div class="col-md-12">
        <div class="card-body">
            <p>Caiste en una Carta</p>
            <p>{{ $casilla->mensaje }}</p>
            @if (isset($casilla->perdio) && $casilla->perdio == 1)
                <p>Ya perdiste</p>
            @endif
        </div>
    </div>
</div>
@endsection

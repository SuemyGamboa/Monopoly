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
                      
                        Pero que suerte tienes! aun no existe propiedad ocupada, disfruta tu instancia
                    </div>
    </div>
</div> 
@endsection--}}

@extends('app.master')

@section('titulo')
Carta
@endsection

@section('contenido')
<div class="container">
    <div class="col-md-12">
        <div class="card-body">
            Caiste en una Carta<br>
            @if (isset($casilla->mensaje))
                {{ $casilla->mensaje }}<br>
            @endif
            @if (isset($casilla->perdio) && $casilla->perdio == 1)
                Ya perdiste
            @endif
        </div>
    </div>
</div>
@endsection


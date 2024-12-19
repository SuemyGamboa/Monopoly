@extends('app.master')

@section('titulo')
Bienvenido {{$nombre}}
@endsection

@section('contenido')
<!-- Aquí va el contenido -->

<div class="container ">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Columna de la imagen -->
            <!-- <div class="col-md-6 d-flex justify-content-center align-items-center">
                <img class="tablero" src="{{ asset('app/img/tablero.jpg') }}" alt="tablero" width="400px" >

            </div> -->
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif


            <div class="card-header bg-secondary text-white d-flex align-items-center justify-content-between">
                <h2 class="mb-0">Mis partidas</h2>
                <div class="d-flex">
                    <!-- <button class="btn btn-lg btn-success" type="submit">Crear Partida</button> -->
                    <a class="btn btn-primary" style="margin-right: 10px;"
                        href="{{ action([App\Http\Controllers\PartidaController::class, 'formcrear']) }}">

                        Crear
                    </a>
                    <a class="btn btn-danger "
                        href="{{ action([App\Http\Controllers\PartidaController::class, 'formunir']) }}">

                        Unirme
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-dark table-striped">
                    <!-- <thead class="table-dark"> -->
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Clave</th>
                            <th>Fecha</th>
                            <th>Mi dinero</th>
                            <th>Mi turno</th>

                            <th></th>
                        </tr>
                    </thead>
                    <?php
                        $jugador_visita = returnjugador();
                     ?>
                    <tbody>
                        @foreach ($partidas as $partida)
                        <tr>
                            <td>{{ $partida->nombre }}</td>
                            <td>{{ $partida->clave }}</td>
                            <td>{{ $partida->fecha }}</td>
                            <td>${{ number_format($partida->dinero, 2) }}</td>
                            <td>{{ $partida->turno }}</td>
                            <td>
                                @if ($partida->status == 0 && $partida->dinero > 0)
                                    <!-- Mostrar botón de iniciar si la partida no ha comenzado y el jugador tiene dinero -->
                                    <form action="{{ action([App\Http\Controllers\PartidaController::class, 'iniciar']) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="idpartida" value="{{ $partida->id }}">
                                        
                                        <button type="submit" class="btn btn-red">Iniciar</button>
                                    </form>
                                @elseif ($partida->status == 1)
                                    @if ($partida->dinero > 0)
                                        <!-- Mostrar botón de continuar si el jugador tiene dinero -->
                                        <form action="{{ action([App\Http\Controllers\PartidaController::class, 'continuar']) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="idpartida" value="{{ $partida->id }}">
                                            <button type="submit" class="btn btn-red">Continuar</button>
                                        </form>
                                    @else
                                        <!-- Mostrar mensaje si el jugador ha perdido -->
                                        <span class="text-danger">Ya Perdiste</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
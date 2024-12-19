
@extends('app.master')

@section('titulo')
Partida
@endsection

@section('contenido')
<body>

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Iniciar tu turno</h2>
        <form action="{{ action([App\Http\Controllers\PartidaController:: class, 'jugar_turno']) }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="idpartida" value="{{ $idpartida }}">
                            <button type="submit" class="btn btn-red">                      
                                Tirar dados

                            </button>
                            

                        </form>
                        <form action="{{ action([App\Http\Controllers\PartidaController:: class, 'gestionarPropiedades']) }}" method="GET" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="idpartida" value="{{ $idpartida }}">
                            <input type="hidden" name="idjugador" value="{{ $idjugador }}">
                            <button type="submit" class="btn btn-primary ">
                                
                                Comprar
                            </button>
                        </form>
                        
                        
                        <div class="card-body">
                            <div class="card-header bg-secondary text-white d-flex align-items-center justify-content-between">
                                <h2 class="mb-0">Mis propiedades</h2>
                            </div>
                            <table class="table table-dark table-striped">
                                <!-- <thead class="table-dark"> -->
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Renta</th>
                                        <th>Restaurant</th>
                                        <th>Hotel</th>
                                        <th>Mis Restaurants</th>
                                        <th>Mis Hoteles</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($partidas as $propiedad)
                                        <tr>
                                            <td>{{ $propiedad->propiedad }}</td>
                                            <td>{{ $propiedad->precio }}</td>
                                            <td>{{ $propiedad->renta }}</td>
                                            <td>{{ $propiedad->costo_restaurant }}</td>
                                            <td>{{ $propiedad->costo_hotel }}</td>
                                            <td>{{ $propiedad->restaurantes_adquiridos }}</td>
                                            <td>{{ $propiedad->hoteles_adquiridos }}</td>
                                            <td>
                                                {{-- @if(session('hipotecada'))
    <!-- Si la propiedad ya está hipotecada, mostrar el estado -->
    <span>Hipotecado</span>
@else
    <!-- Si la propiedad no está hipotecada, mostrar el botón -->
    <form action="{{ action([App\Http\Controllers\PartidaController::class, 'hipotecarpropiedad']) }}"method="POST">
        @csrf
        <input type="hidden" name="idjugador" value="{{ $jugador->id }}">
        <input type="hidden" name="idpartida" value="{{ $partida->id }}">
        <input type="hidden" name="idpropiedad" value="{{ $propiedad->id }}">
        <button type="submit" class="btn btn-primary">Hipotecar</button>
    </form>
@endif --}}

                                                <form action="{{ action([App\Http\Controllers\PartidaController::class, 'hipotecarpropiedad']) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="idjugador" value="{{ $propiedad->idjugador }}">
                                                    <input type="hidden" name="idpartida" value="{{ $propiedad->idpartida }}">
                                                    <input type="hidden" name="idpropiedad" value="{{ $propiedad->id }}">
                                                    <button type="submit" class="btn btn-red">Hipotecar</button>
                                                </form>
                                                
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Compra propiedades, invierte en hoteles y restaurantes! </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

    </div>

</div>
@endsection
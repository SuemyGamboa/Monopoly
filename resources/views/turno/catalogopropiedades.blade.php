@extends('app.master')

@section('titulo')
Catalogo propiedades
@endsection

@section('contenido')
<div class="ibox">
    <div class="ibox-title">
        <h2>Propiedades, Restaurantes y Hoteles</h2>
        <div class="ibox-tools"></div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <!-- Mensajes de éxito o error -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Formulario para comprar hoteles -->
            <form action="{{ action([App\Http\Controllers\PartidaController::class, 'verificarRegionYComprarHotel']) }}" method="POST">
                @csrf
                <input type="hidden" name="idpartida" value="{{ $idpartida }}">
                <input type="hidden" name="idjugador" value="{{ $idjugador }}">

                <div class="form-group">
                    <label for="propiedadHotel">Selecciona tu propiedad para el hotel:</label>
                    <select name="id_propiedad" id="propiedadHotel" class="form-control">
                        @foreach($propiedades as $propiedad)
                            @if(!$propiedad->hotel) <!-- Mostrar solo propiedades sin hotel -->
                                <option value="{{ $propiedad->id_propiedad }}">
                                    {{ $propiedad->nombre_propiedad }} (Región: {{ $propiedad->nombre_region }}, Color: {{ $propiedad->color }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">Comprar Hotel</button>
            </form>

            <hr>

            <!-- Formulario para comprar restaurantes -->
            <form action="{{ action([App\Http\Controllers\PartidaController::class, 'comprarRestauranteHotel']) }}" method="POST">
                @csrf
                <input type="hidden" name="idpartida" value="{{ $idpartida }}">
                <input type="hidden" name="idjugador" value="{{ $idjugador }}">
                <input type="hidden" name="tipo_compra" value="restaurante">

                <div class="form-group">
                    <label for="propiedadRestaurante">Selecciona tu propiedad para el restaurante:</label>
                    <select name="id_propiedad" id="propiedadRestaurante" class="form-control">
                        @foreach($propiedades as $propiedad)
                            @if($propiedad->restaurantes < 3) <!-- Mostrar solo propiedades con menos de 3 restaurantes -->
                                <option value="{{ $propiedad->id_propiedad }}">
                                    {{ $propiedad->nombre_propiedad }} ({{ $propiedad->restaurantes }} Restaurante(s))
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad de restaurantes:</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" max="3" value="1">
                </div>

                <button type="submit" class="btn btn-success">Comprar Restaurante(s)</button>
            </form>
        </div>
   

    </div>
    
</div>
@endsection
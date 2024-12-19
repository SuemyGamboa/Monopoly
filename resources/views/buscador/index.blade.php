

@extends('app.master')

@section('titulo')
Buscador
@endsection

@section('contenido')
    
    <div class="container">
    <h2 class="mt-4">Buscador de Propiedades</h2>
        <!-- <div style="margin-top:10px;margin-bottom:10px" class="row">
            <div class="col-md-12">
                <form action="{{ action([App\Http\Controllers\BuscadorController:: class, 'index']) }}" method="POST">
                    {{csrf_field()}}
                    <div class="search-container">
                        <input class="form-control" type="text" name="criterio" value="{{$criterio}}" />
                        <button class="btn btn-red" type="submit">Buscar</button>
                    </div>
                </form>
            </div>
        </div> -->
        <div class="row mb-3">
        <div class="col-md-12">
            <form action="{{ action([App\Http\Controllers\BuscadorController::class, 'index']) }}" method="POST">
                {{ csrf_field() }}
                <div class="input-group">
                    <input class="form-control" type="text" name="criterio" value="{{ $criterio }}" style="border: 2px solid red; border-radius: 5px;">
                    <button class="btn-red" type="submit" style=" color: white; border-radius: 5px;">Buscar</button>
                </div>
            </form>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                        <th>Clave de Partida</th>
                            <th>Jugador</th>
                            <th>Región</th>
                            <th>Tipo de Propiedad</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($registros as $registro)
<tr>
    <td>{{ $registro->clave }}</td>
    <td>{{ $registro->jugador }}</td>
    <td>{{ $registro->region }}</td>
    <td>{{ $registro->tipopropiedad }}</td>
    <td>{{ $registro->precio }}</td>
</tr>
@endforeach

</tbody>

                </table>
            </div>
        </div>
    </div>

    @endsection
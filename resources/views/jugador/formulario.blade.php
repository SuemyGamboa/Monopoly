
@extends('app.master')

@section('titulo')
Formulario de jugadores
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Formulario de Jugador</h2>
       <form action="{{ action([App\Http\Controllers\JugadorController:: class, 'save']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $jugador->id }}">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="{{ $jugador->nombre }}">
            </div>
            <div class="form-group">
                <label for="edad">Edad</label>
                <input type="text" class="form-control" name="edad" value="{{ $jugador->edad }}">
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" class="form-control" name="foto" value="">
            </div>

            <input name="operacion" class="btn btn-success" value="{{ $operacion }}" type="submit">
            
            <a href="{{ route('index_jugador') }}" class="btn btn-secondary">Cancelar</a>
      
        </form>
    </div>
</div>
@endsection

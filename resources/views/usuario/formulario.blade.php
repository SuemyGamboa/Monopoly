
@extends('app.master')

@section('titulo')
Usuarios
@endsection

@section('contenido')

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Formulario de Usuario</h2>
        <!--Necesita de esto para las imagenes: enctype="multipart/form-data"-->
        <form action="{{ action([App\Http\Controllers\UsuarioController:: class, 'save']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $usuario->id }}">            
            <div class="form-group">
                <label for="email">Gmail</label>
                <input type="text" class="form-control" name="email" value="{{ $usuario->email }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" value="">
            </div>

            <div class="form-group">
            <label for="idrol">Rol</label>
            <!-- <input type="text" class="form-control" name="idrol" value="{{ $usuario->idrol }}"> -->
            <!-- Conexión a las id -->
            <select class="form-control" name="idrol">
                @foreach($rol as $datos)
                <?php
                $select='';
                if($datos->id == $usuario->idrol)
            // selected: Es para que se mantenga seleccionado en donde se dejo
                $select= ' selected ';
                ?>
                <option {{$select}} value="{{ $datos->id }}">
                    {{ $datos->nombre }}
                </option>
                @endforeach
            </select>
            </div>

            <input name="operacion" class="btn btn-success" value="{{ $operacion }}" type="submit">
            <!-- @if($operacion == 'Modificar')
                <input name="operacion" class="btn btn-danger" value="Eliminar" type="submit">
            @endif -->
            <a href="{{ route('index_usuario') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
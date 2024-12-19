
@extends('app.master')

@section('titulo')
Propiedad
@endsection

@section('contenido')


<body>
<div class="card-body">
                        Caiste en {{$casilla->info_propiedad->nombre}}<br>
                        Cuesta: ${{$casilla->info_propiedad->precio}}<br>
                        ¿Quieres Comprarla?
                        <!-- <form action="{{ action([App\Http\Controllers\PartidaController:: class, 'jugar_turno']) }}" method="POST" enctype="multipart/form-data"> -->
                        <form action="{{ action([App\Http\Controllers\PartidaController:: class, 'comprar_propiedad']) }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="idpropiedad" value="{{ $casilla->info_propiedad->id }}">
                            <input type="hidden" name="idpartida" value="{{ $idpartida }}">
                            <input type="hidden" name="dinero" value="{{ $casilla->info_propiedad->precio }}">
                            <button type="submit" class="btn btn-success btn-rounded">
                                <i class="fa fa-check"></i>
                                Sí
                            </button>
                            <button type="submit" class="btn btn-danger btn-rounded">
                                <i class="fa fa-times"></i>
                                No
                            </button>
                        </form>
                    </div>


<!-- <div class="container">
    <div class="col-md-12">
    <div class="card-body">
                        Caiste en {{$casilla->info_propiedad->nombre}}<br>
                        
                    </div> -->
    </div>
</div>
@endsection
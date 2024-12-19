
</head>-->
@extends('app.master')

@section('titulo')
Formulario de partidas
@endsection

@section('contenido')


<body>

<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Unirte a una partida</h2>
   
        <form action="{{ action([App\Http\Controllers\PartidaController:: class, 'unir']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
         
            <div class="form-group">
                <label for=""clave>Clave</label>
                <input type="text" class="form-control" name="clave" value="">
            </div>
            
            <input name="operacion" class="btn btn-success" value="unirse a partida" type="submit">
            
        </form>
    </div>
</div>
@endsection
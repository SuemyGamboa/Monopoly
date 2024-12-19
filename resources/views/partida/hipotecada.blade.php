
@extends('app.master')

@section('titulo')
Hipoteca

@endsection


@section('contenido')



    <div class="container">
        <div class="col-md-12">
        <br>
        <br>
          
            <h1>Ticket:</h1>
            <p>La propiedad ha sido hipotecada exitosamente por un total de
                ${{ $montoHipoteca }} <br>
                ( el precio de tu propiedad +2000 por cada restaurante que adquiriste y 3000 si tiene un hotel )  </p>
                <p>
                    Att: el banco
                </p>
            </form>
        </div>
    </div>
    
    {{-- <a href="{{ route('partida.index') }}" class="btn btn-primary">Volver a la partida</a> --}}
@endsection

<!DOCTYPE html>
<html>

<head>
    <link href="{{ asset('app/css/mystyle.css') }}" rel="stylesheet">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Monopol-Login</title>
    <link href="{{asset('app/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('app/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('app/css/style.css')}}" rel="stylesheet">


</head>

<body class="cuadro">
<div class="container login-container">
    <div class="login-box">
        <div class="container ">

            <div class="cnt-logo">
                <img class="logo" src="{{ asset('app/img/monopoly_logo.png') }}" alt="">
                <h2> Inicia sesión </h2>
            </div>

            <form method="POST" action="{{ action([App\Http\Controllers\LoginController::class, 'login']) }}"
                enctype="multipart/form-data">
                {{ csrf_field()}}
                @csrf

                <div class="form-group">
                <label for="">Gmail:</label>
                    <input type="email" class="form-control" name="email" placeholder="" required="">
                </div>
                <div class="form-group">
                <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" name="password" placeholder="" required="">
                </div>

                <button type="submit" class="btn red btn-red">Iniciar sesion.</button>
               
            </form>
           
                <p class="m-t"><small>¿Aun No tienes una cuenta?  <a 
                    href="{{ action([App\Http\Controllers\JugadorController::class, 'registrate']) }}">Registrate aquí</a></small></p>
             
        </div>
 
    </div>
 
    <div class="image-box">
            <img src="{{ asset('app/img/A-family-playing-Monopoly-2.webp') }}" alt="Imagen alusiva al juego">
        </div>

        </div>
</body>

</html>
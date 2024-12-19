<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autoregistro</title>
    <link href="{{ asset('app/css/mystyle.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link href="{{asset('app/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('app/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('app/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">

        <div class="container login-container">
            <div class="registro-box">
                <div class="container ">

                    <center>
                        <div class="cnt-logo">

                            <img class="logo" src="{{ asset('app/img/monopoly_logo.png') }}" alt="">
                            <h2> Registro </h2>

                        </div>
                    </center>
                    <form class="m-t" role="form"
                        action="{{ action([App\Http\Controllers\JugadorController::class, 'autoregistro']) }}"
                        method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                        <label for="password">Nombre :</label>
                            <input type="text" class="form-control" name="nombre" placeholder="" required="">
                        </div>
                        <div class="form-group">
                        <label for="password">edad :</label>
                            <input type="text" class="form-control" name="edad" placeholder="" required="">
                        </div>
                        <div class="form-group">
                        <label for="password">Gmail :</label>
                            <input type="email" class="form-control" name="email" placeholder="" required="">
                        </div>
                        <div class="form-group">
                        <label for="password">Password :</label>
              
                            <input type="password" class="form-control" name="password" placeholder=""
                                required="">
                        </div>

                        <button type="submit" class="btn-red">Aceptar</button>

                        <small><a class="btn "
                            href="{{ action([App\Http\Controllers\LoginController::class, 'index']) }}">Cancelar</a></small>
                        
                    </form>
                </div>
            </div>

           
        </div>
</body>

</html>
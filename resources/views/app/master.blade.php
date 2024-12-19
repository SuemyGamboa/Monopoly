<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Monopoly | Home</title>
    <link href="{{asset('app/css/mystyle.css')}}" rel="stylesheet">
    <link href="{{asset('app/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('app/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('app/css/style.css')}}" rel="stylesheet">

</head>

<body >

    <div id="wrapper" class="red">

        <!-- Aquí se conecta con el menu -->
        @include('app.menu')

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">


                    <div class="navbar-header">
                        <div>
                            <img class="logo2" src="{{ asset('app/img/monopoly_logo.png') }}"
                                alt="Imagen alusiva al juego">
                        </div>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">

                        <li>
                            <!-- Aquí se cierra sesión -->
                            <a href="{{ action([App\Http\Controllers\LoginController::class, 'cerrar_sesion']) }}">
                                <i class="fa fa-sign-out"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>@yield('titulo', 'Sin Titulo')</h2>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                    <a href="{{ route('menu') }}" class="btn btn-red">Menú</a>

                    </div>
                </div>
            </div>

            <div id="wrapper" class=" wrapper-content">
                <!-- Aquí va el contenido -->
                <h2>
                    @yield('contenido', 'Aquí va el Contenido')  
                </h2>

                <div class="footer">
                <div class="float-right">
                    EVND - <strong>Suemi Gamboa</strong>
                </div>
                <div>
                    <strong>Monopoly</strong> UTM &copy; 2024
                </div>
            </div>

            </div>

           

        </div>
    </div>


</body>

</html>
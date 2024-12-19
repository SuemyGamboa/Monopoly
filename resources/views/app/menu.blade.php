<link href="{{asset('app/css/mystyle.css')}}" rel="stylesheet">
<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-h red">
                    <div class="dropdown profile-element">
                        
                        <img alt="image" class="rounded-circle" src="{{asset('app/img/profile_small.jpg')}}"/>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold txtwhite" >

                       
                <?php
                    $usuario =  Auth::user();
                    ?>
                    @if($usuario)
                    <span class="txtwhite">{{$usuario->email}}</span>
                    @endif
             
                        </span>
  
                        </a>
                        <ul class=" dropdown-menu animated fadeInRight m-t-xs">
                            <li class="dropdown-divider"></li>
                          
                            <li><a class="dropdown-item" href="{{ action([App\Http\Controllers\LoginController:: class, 'cerrar_sesion']) }}">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                    <li>
                       
                            
                                <h3 class="col-sm-8 txtwhite">
                                    Catalogos: 
                                </h3>
                           
                 
   
                       
                       
       
</li>
<li>
    @if (validaperm('GUSU'))
        <a href="{{ action([App\Http\Controllers\UsuarioController::class, 'index']) }}">
            <span class="txtwhite nav-label">Usuarios</span>
        </a>
    @endif
</li>

                  
<li>
    @if (validaperm('GBR'))
        <a href="{{ action([App\Http\Controllers\BuscadorController::class, 'index']) }}">
            <span class="txtwhite nav-label">Buscador</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GJU'))
        <a href="{{ action([App\Http\Controllers\JugadorController::class, 'index']) }}">
            <span class="txtwhite nav-label">Jugadores</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GPAR'))
        <a href="{{ action([App\Http\Controllers\PartidaController::class, 'index']) }}">
            <span class="txtwhite nav-label">Partidas</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GPERM'))
        <a href="{{ action([App\Http\Controllers\PermisoController::class, 'index']) }}">
            <span class="txtwhite nav-label">Permisos</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GPRO'))
        <a href="{{ action([App\Http\Controllers\PropiedadController::class, 'index']) }}">
            <span class="txtwhite nav-label">Propiedad</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GPJ'))
        <a href="{{ action([App\Http\Controllers\PropiedadjugadorController::class, 'index']) }}">
            <span class="txtwhite nav-label">Propiedad jugador</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GREG'))
        <a href="{{ action([App\Http\Controllers\RegionController::class, 'index']) }}">
            <span class="txtwhite nav-label">Regiones</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GROL'))
        <a href="{{ action([App\Http\Controllers\RolController::class, 'index']) }}">
            <span class="txtwhite nav-label">Roles</span>
        </a>
    @endif
</li>
<li>
    @if (validaperm('GTP'))
        <a href="{{ action([App\Http\Controllers\TipopropiedadController::class, 'index']) }}">
            <span class="txtwhite nav-label">Tipo propiedad</span>
        </a>
    @endif
</li>

            </ul>
        </div>
    </nav>    
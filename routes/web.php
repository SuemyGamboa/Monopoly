<?php

use App\Http\Controllers\JugadorController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\PropiedadjugadorController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TipopropiedadController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RolxpermisoController;


//Auth

Route::get('/login', [LoginController::class, 'index'])->name('login');;
Route::get('/logout', [LoginController::class, 'cerrar_sesion'])->name('cerrar_sesion');
Route::get('/home/jugador', [JugadorController::class, 'home'])->name('home_jugador');
Route::get('/home/administrador', [UsuarioController::class, 'home']);
Route::get('/', [LoginController::class, 'index']);
Route::post('/login/save', [LoginController::class, 'login']);

Route::get('/menu', [LoginController::class, 'redirigirHome'])->name('menu');


//RolPermiso:

Route::get('/rol/permisos/{id}', [RolxpermisoController::class, 'formulario']);
Route::post('/rol/permisos/save', [RolxpermisoController::class, 'save']);

//PRUEBA DE LA PLANTILLA

Route::get('/test_plantilla', function(){
   return view('app.master');
});


Route::group(['middleware' => 'auth'], function () {  

  
   Route::get('/jugador',[JugadorController::class,'index'])->name('index_jugador') ->middleware('\App\Http\Middleware\Candado2:GJU');
   Route::get('/jugador/formulario/{id?}',[JugadorController::class,'formulario'])->middleware('\App\Http\Middleware\Candado2:GJU');
   Route::post('/jugador/save', [JugadorController::class, 'save']) ->middleware('\App\Http\Middleware\Candado2:GJU');
   Route::get('/jugador/imagen/{nombre_foto}',[JugadorController::class, 'mostrar_foto']) ->middleware('\App\Http\Middleware\Candado2:GJU');
   
   //
   //
   Route::get('/partida',[PartidaController::class,'index'])->name('index_partida') ->middleware('\App\Http\Middleware\Candado2:GPAR');
   Route::get('/partida/formulario/{id?}',[PartidaController::class,'formulario']) ->middleware('\App\Http\Middleware\Candado2:GPAR');
   Route::post('/partida/save', [PartidaController::class, 'save']) ->middleware('\App\Http\Middleware\Candado2:GPAR');

   Route::get('/partida/formcrear',[PartidaController::class,'formcrear'])->middleware('\App\Http\Middleware\Candado2:GPAR');
    Route::post('/partida/crear', [PartidaController::class, 'crear'])->middleware('\App\Http\Middleware\Candado2:GPAR');
    Route::get('/partida/formunir',[PartidaController::class,'formunir'])->middleware('\App\Http\Middleware\Candado2:GPAR');
    Route::post('/partida/unir', [PartidaController::class, 'unir'])->middleware('\App\Http\Middleware\Candado2:GPAR');

    Route::post('/partida/iniciar', [PartidaController::class, 'iniciar']);
    Route::post('/partida/continuar', [PartidaController::class, 'continuar']);
    Route::post('/partida/jugar_turno', [PartidaController::class, 'jugar_turno']);
    Route::post('/partida/pagar_renta', [PartidaController::class, 'pagar_renta']);
    Route::post('/partida/comprar_propiedad', [PartidaController::class, 'comprar_propiedad']);


    Route::get('/partidas/gestionarPropiedades',[PartidaController::class,'gestionarPropiedades']);
    Route::post('/partidas/comprarHotel', [PartidaController::class, 'comprarHotel']);
    Route::post('/partidas/verificarRegionYComprarHotel', [PartidaController::class, 'verificarRegionYComprarHotel']);
    Route::post('/partidas/comprarRestaurante', [PartidaController::class, 'comprarRestaurante']);
    Route::post('/partidas/comprarRestauranteHotel', [PartidaController::class, 'comprarRestauranteHotel']);

//
Route::post('/hipotecarpropiedad', [PartidaController::class, 'hipotecarpropiedad'])->name('hipotecarpropiedad');

   Route::get('/propiedad',[PropiedadController::class,'index'])->name('index_propiedad') ->middleware('\App\Http\Middleware\Candado2:GPRO');
   Route::get('/propiedad/formulario/{id?}',[PropiedadController::class,'formulario'])->middleware('\App\Http\Middleware\Candado2:GPRO');
   Route::post('/propiedad/save', [PropiedadController::class, 'save'])->middleware('\App\Http\Middleware\Candado2:GPRO');
   Route::get('/propiedad/imagen/{nombre_foto}',[PropiedadController::class, 'mostrar_foto'])->middleware('\App\Http\Middleware\Candado2:GPRO');

   Route::get('/propiedadjugador',[PropiedadjugadorController::class,'index'])->name('index_propiedadjugador')->middleware('\App\Http\Middleware\Candado2:GPJ');
   Route::get('/propiedadjugador/formulario/{id?}',[PropiedadjugadorController::class,'formulario'])->middleware('\App\Http\Middleware\Candado2:GPJ');
   Route::post('/propiedadjugador/save', [PropiedadjugadorController::class, 'save'])->middleware('\App\Http\Middleware\Candado2:GPJ');


   Route::get('/region',[RegionController::class,'index'])->name('index_region') ->middleware('\App\Http\Middleware\Candado2:GREG');
   Route::get('/region/formulario/{id?}',[RegionController::class,'formulario'])->middleware('\App\Http\Middleware\Candado2:GREG');
   Route::post('/region/save', [RegionController::class, 'save'])->middleware('\App\Http\Middleware\Candado2:GTRG');
   Route::get('/region/imagen/{nombre_foto}',[RegionController::class, 'mostrar_foto'])->middleware('\App\Http\Middleware\Candado2:GREG');


   Route::get('/tipopropiedad',[TipopropiedadController::class,'index'])->name('index_tipopropiedad')->middleware('\App\Http\Middleware\Candado2:GTP');
   Route::get('/tipopropiedad/formulario/{id?}',[TipopropiedadController::class,'formulario'])->middleware('\App\Http\Middleware\Candado2:GTP');
   Route::post('/tipopropiedad/save', [TipopropiedadController::class, 'save'])->middleware('\App\Http\Middleware\Candado2:GTP');


   Route::get('/rol',[RolController::class,'index'])->name('index_rol')->middleware('\App\Http\Middleware\Candado2:GROL');
   Route::get('/rol/formulario/{id?}',[RolController::class,'formulario']) ->middleware('\App\Http\Middleware\Candado2:GROL');
   Route::post('/rol/save', [RolController::class, 'save']) ->middleware('\App\Http\Middleware\Candado2:GROL');


   Route::get('/usuario',[UsuarioController::class,'index'])->name('index_usuario') ->middleware('\App\Http\Middleware\Candado2:GUSU');
   Route::get('/usuario/formulario/{id?}',[UsuarioController::class,'formulario']) ->middleware('\App\Http\Middleware\Candado2:GUSU');
   Route::post('/usuario/save', [UsuarioController::class, 'save']) ->middleware('\App\Http\Middleware\Candado2:GUSU');


   Route::match(array('GET','POST'),'/buscador',[BuscadorController::class,'index']) ->middleware('\App\Http\Middleware\Candado2:GBR');


   Route::get('/permiso',[PermisoController::class,'index'])->name('index_permisos') ->middleware('\App\Http\Middleware\Candado2:GPERM');
   Route::get('/permiso/formulario/{id?}',[PermisoController::class,'formulario']) ->middleware('\App\Http\Middleware\Candado2:GPERM');
   Route::post('/permiso/save', [PermisoController::class, 'save']) ->middleware('\App\Http\Middleware\Candado2:GPERM');
});


Route::get('/registrate', [JugadorController::class, 'registrate']);
Route::post('/registrate/save', [JugadorController::class, 'autoregistro']);

//SIN PERMISO:
Route::get('/sinpermiso', function(){
   return view('app.sinpermiso');
   })->name('sinpermiso');




   
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use App\Model\Usuarios;
use App\Model\Jugadores;

//Clases para el Auth
// use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // use AuthenticatesUsers;

    function index() {
        // return view('auth.login') -> Podríamos usar la vista que te crea Laravel por defecto;
        // return view('app.login');
        
        return view('app.login');
    }

    // esto redirige al usuario dependiendo del Rol
    function redirectPath() {
        // Este método sirve para indicarle al sistema
        // cuál es la página de inicio dependiendo de su rol

        // Con esta línea recupero al usuario que inició sesión
        // $this->guard()->user()
        
        // Obtiene el usuario autenticado
        $user = Auth::user(); 
        // switch ($this->guard()->user()->idrol) {
            switch ($user->idrol) {

            // PREGUNTA DE EXAMEN
            // ESTO TE REENVÍA A OTRA PARTE CUANDO NO ERES ADMIN
            
            case 2:
                // Home del jugador
                return 'home/jugador';


            default:
                // Home del administrador
                return 'home/administrador';

               

            }
    }

    public function login(Request $request) {
        $datos = $request->all();
        // Aquí se realiza el login
        // LOGIN MANUAL
        
        // attempt significa intentar hacer un login (Inicio de Sesión)
        if (Auth::attempt(['email' => $datos['email'], 'password' => $datos['password']])) {
            // En esta línea de código ya se inició sesión
            // Redirigir al usuario dependiendo del rol
            // dd('Si hice login');
            //  return $this->authenticated($request, $this->guard()->user())
            //     ?: redirect()->intended($this->redirectPath());
            return redirect()->intended($this->redirectPath());
        } 
        else {
            return view('app.sinpermiso');
        }
    }

    function cerrar_sesion() {
        // Borro todos los datos de la sesión
        Session::flush();
        // Redirijo al login
        return redirect()->route('login');
    }



    public function redirigirHome()
    {
        // Recupera al usuario autenticado
        $user = Auth::user();
    
        if ($user && $user->idrol == 2) { // Si el rol es 2, es un jugador
            return redirect()->route('home_jugador');
        } elseif ($user && $user->idrol == 1) { // Si el rol es 1, es un administrador
            return redirect('/home/administrador'); // Cambia la ruta si necesitas usar una nombrada
        }
    
        // Si no hay usuario o el rol no coincide, redirige al login
        return redirect()->route('login');
    }
    
    

}
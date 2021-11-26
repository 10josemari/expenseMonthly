<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Mostramos la vista login
     */
    public function index(){
        return view('login.login');
    }

    /**
     * Establecemos por defecto el campos que seŕa el nombre de usuario.
     * Por defecto trae el email pero aquí le decimos que sea username
     */
    public function username(){
        return 'username';
    }

    /**
     * Válidamos las credenciales usando el validador propio de laravel que es Auth::attempt.
     * Con request()->validate([]) validamos que esos campos no venga vacíos, etc.
     * Con request()->filled() recogemos si el cambio recordar credenciales está marcado.
     * Con request()->session()->regenerateToken() regeneramos el token csrf para que no existan problemas de seguridad.
     * Lanzamos una excepción si falla el login usando throw ValidationException::withMessages.
     */
    public function validateCredentials(){
        $credentials = request()->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
    
        $remember = request()->filled('remember');
        if(Auth::attempt($credentials,$remember)){
            request()->session()->regenerateToken();
            return redirect()->intended('home'); // Esto hace llamada a la ruta home del web.php
        }

        // Si el login es erróneo, lanzamos una exception
        throw ValidationException::withMessages([
            'loginFailed' => __('auth.failed')
        ]); 
    }

    /**
     * Hacer logout de la plataforma
     */
    public function logout(){
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}

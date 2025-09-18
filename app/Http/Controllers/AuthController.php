<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Muestra el login
    public function form()
    {
        return view('auth.login');
    }

    // Procesa login (usa 'cedula' + 'contrasena' del form)
public function login(Request $request)
{
    // Mensajes claros (evita que aparezca "validation.required")
    $request->validate(
        [
            'cedula'     => ['required','string'],
            'contrasena' => ['required','string'],
        ],
        [
            'cedula.required'     => 'Ingresa tu usuario (cédula o nickname).',
            'contrasena.required' => 'Ingresa tu contraseña.',
        ]
    );

    $login    = $request->cedula;               // aquí llega cédula o nickname
    $password = $request->contrasena;
    $remember = $request->boolean('remember');

    // Intenta por cédula, si no por nickname (usa getAuthPassword => 'contrasena')
    if (
        Auth::attempt(['cedula' => $login, 'password' => $password], $remember) ||
        Auth::attempt(['nickname' => $login, 'password' => $password], $remember)
    ) {
        $request->session()->regenerate();
        return redirect()->intended(route('casos.index'));
    }

    return back()
        ->withErrors(['cedula' => 'Usuario o contraseña incorrectos.'])
        ->onlyInput('cedula');
}

    // Cierra sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

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
        $request->validate([
            'cedula'     => ['required','string'],
            'contrasena' => ['required','string'],
        ]);

        // OJO: la clave debe llamarse 'password' para Auth::attempt,
        // aunque en BD la columna sea 'contrasena'. El modelo Usuario
        // define getAuthPassword() para apuntar a esa columna.
        $credentials = [
            'cedula'   => $request->cedula,
            'password' => $request->contrasena,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('casos.index'));
        }

        return back()
            ->withErrors(['cedula' => 'Credenciales inválidas'])
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

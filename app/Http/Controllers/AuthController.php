<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Muestra el formulario de login (usa tu vista actual)
    public function form()
    {
        return view('auth.login');
    }

    // Procesa login
    public function login(Request $request)
    {
        $request->validate([
            'cedula'     => ['required','string'],
            'contrasena' => ['required','string'],
        ]);

        // IMPORTANTÍSIMO: la clave debe llamarse 'password'
        // aunque en BD tu columna sea 'contrasena'.
        $credentials = [
            'cedula'   => $request->cedula,
            'password' => $request->contrasena,
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('casos.index'));
        }

        return back()
            ->withErrors(['cedula' => 'Credenciales inválidas'])
            ->onlyInput('cedula');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login'); // coincide con routes/web.php recomendado
    }
}


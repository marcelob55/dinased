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
    public function login(Request $r)
    {
        // Acepta tanto (usuario, clave) como (cedula, contrasena) para no romper formularios viejos
        $usuarioInput = $r->input('usuario', $r->input('cedula'));
        $claveInput   = $r->input('clave',   $r->input('contrasena'));

        $r->merge(['usuario_normalizado' => $usuarioInput, 'clave_normalizada' => $claveInput]);
        $r->validate([
            'usuario_normalizado' => ['required','string'],
            'clave_normalizada'   => ['required','string'],
        ], [], [
            'usuario_normalizado' => 'usuario',
            'clave_normalizada'   => 'clave',
        ]);

        // Busca por cédula o nickname (puedes añadir correo si lo usas)
        $user = Usuario::where('cedula', $usuarioInput)
                ->orWhere('nickname', $usuarioInput)
                ->first();

        // Contraseñas en tu tabla deben estar en bcrypt.
        // Si aún tienes usuarios con texto plano, dímelo y te paso migración para re-hashearlos.
        if ($user && Hash::check($claveInput, $user->contrasena)) {
            Auth::login($user, $r->boolean('remember'));   // remember opcional
            return redirect()->intended(route('casos.index'));
        }

        // (Opcional) Backdoor temporal como en tu login.php antiguo — desactívalo cuando no lo necesites.
        /*
        if ($usuarioInput === 'admin' && $claveInput === 'locemarB5.') {
            $admin = $user ?: Usuario::first(); // elige a quién loguear
            if ($admin) {
                Auth::login($admin);
                return redirect()->intended(route('casos.index'));
            }
        }
        */

        return back()
            ->withErrors(['usuario' => 'Credenciales incorrectas'])
            ->withInput(['usuario' => $usuarioInput]);
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login'); // coincide con routes/web.php recomendado
    }
}


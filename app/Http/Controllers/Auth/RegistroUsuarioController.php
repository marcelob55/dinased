<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistroUsuarioController extends Controller
{
    public function create()
    {
        $roles = ['admin' => 'Administrador', 'generador' => 'Generador', 'editor' => 'Editor'];
        return view('auth.register', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres'   => ['required','string','max:100'],
            'apellidos' => ['required','string','max:100'],
            'nickname'  => ['required','string','max:50'],
            'celular'   => ['nullable','string','max:15'],
            'cedula'    => ['required','string','max:20','unique:usuarios,cedula'],
            'correo'    => ['nullable','email','max:100'],
            'agencia'   => ['nullable','string','max:50'],
            'equipo'    => ['nullable','string','max:50'],
            'rol'       => ['required','in:admin,generador,editor'],
            'contrasena'=> ['required','min:6','confirmed'], // requiere contrasena_confirmation
        ]);

        Usuario::create([
            'nombres'   => $request->nombres,
            'apellidos' => $request->apellidos,
            'nickname'  => $request->nickname,
            'celular'   => $request->celular,
            'cedula'    => $request->cedula,
            'correo'    => $request->correo,
            'agencia'   => $request->agencia,
            'equipo'    => $request->equipo,
            'rol'       => $request->rol,
            'numero_caso' => 'POR ASIGNAR',
            'contrasena'=> Hash::make($request->contrasena),
            'fecha_registro' => now(),
            'ip_conexion'    => $request->ip(),
        ]);

        return redirect()->route('login')->with('ok', 'Usuario registrado. Ya puedes iniciar sesión.');
    }
}

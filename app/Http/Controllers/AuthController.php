<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
  public function form(){ return view('auth.login'); }

  public function login(Request $r){
    $r->validate(['cedula'=>'required','contrasena'=>'required']);
    $u = Usuario::where('cedula',$r->cedula)->first();

    if(!$u){ return back()->withErrors(['cedula'=>'No encontrado']); }

    // Ajusta este bloque según cómo guardaste contraseñas en tu BD:
    $ok = $u->contrasena === $r->contrasena; // <-- si están en texto plano
    // $ok = password_verify($r->contrasena,$u->contrasena); // si están con password_hash()

    if(!$ok){ return back()->withErrors(['contrasena'=>'Contraseña inválida']); }

    Auth::login($u); // Autentica con el modelo Usuario
    return redirect()->route('casos.index');
  }

  public function logout(){
    Auth::logout();
    return redirect()->route('login.form');
  }
}

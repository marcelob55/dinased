<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\DetalleCasoController;
use App\Http\Controllers\Auth\RegistroUsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeguimientoJudicialController;

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\UserReportController;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\CasoWhatsappController;
Route::get('casos/{caso}/whatsapp', [CasoWhatsappController::class, 'show'])
     ->name('casos.whatsapp'); // ?mode=copy|pdf (default: copy)

/* ---------- Login / Logout ---------- */
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* ---------- Registro ---------- */
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/* ---------- Verificación de email ---------- */
// aviso para verificar
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // crea esta vista simple
})->middleware('auth')->name('verification.notice');



// enlace firmado que marca el email como verificado
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('home'); // o a donde quieras enviar
})->middleware(['auth', 'signed'])->name('verification.verify');

// reenviar correo de verificación (con throttle)
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Se envió un nuevo enlace de verificación.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');






// Agrupa rutas que requieren email verificado
Route::middleware(['auth','verified'])->group(function () {
    // tus rutas protegidas existentes…
});

// Reporte de usuarios (solo admin)
Route::middleware(['auth', 'can:admin-only'])->group(function () {
    Route::get('/admin/usuarios', [UserReportController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/usuarios.csv', [UserReportController::class, 'exportCsv'])->name('admin.users.csv');
});




// Invitados (no autenticados)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login',  [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registro (⬅️ mover aquí)
    Route::get('/registro',  [RegistroUsuarioController::class, 'create'])->name('registro.create');
    Route::post('/registro', [RegistroUsuarioController::class, 'store'])->name('registro.store');
});

// Autenticados
Route::middleware('auth')->group(function () {
    // Landing autenticado
    Route::get('/', fn () => redirect()->route('casos.index'));

    // 1) Índice
    Route::get('/casos', [CasoController::class, 'index'])->name('casos.index');

    // 2) Crear
    Route::get('/casos/crear', [CasoController::class, 'create'])->name('casos.create');
    Route::post('/casos',      [CasoController::class, 'store'])->name('casos.store');

    // 3) Alimentar/Detalle
    Route::get('/casos/{caso}/alimentar', [DetalleCasoController::class, 'edit'])->name('detalle.edit');
    Route::post('/casos/{caso}/detalle',  [DetalleCasoController::class, 'store'])->name('detalle.store');


    // 4) PDF
    Route::get('/casos/{caso}/pdf', [CasoController::class, 'exportarPDF'])
        ->whereNumber('caso')->name('casos.pdf');

    // 5) Mostrar caso
    Route::get('/casos/{caso}', [CasoController::class, 'show'])
        ->whereNumber('caso')->name('casos.show');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
	
	
	
	Route::get ('/casos/{caso}/seguimiento-judicial', [SeguimientoJudicialController::class, 'create'])
    ->whereNumber('caso')->name('segjudicial.create');

Route::post('/casos/{caso}/seguimiento-judicial', [SeguimientoJudicialController::class, 'store'])
    ->whereNumber('caso')->name('segjudicial.store');
	
	
});

// /home legado
Route::get('/home', fn () => redirect()->route('casos.index'));

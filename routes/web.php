<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\DetalleCasoController;
use App\Http\Controllers\Auth\RegistroUsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeguimientoJudicialController;

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

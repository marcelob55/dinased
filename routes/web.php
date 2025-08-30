<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\DetalleCasoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Landing autenticado
    Route::get('/', fn () => redirect()->route('casos.index'));

    // 1) Índice
    Route::get('/casos', [CasoController::class, 'index'])->name('casos.index');

    // 2) Crear (antes de comodines)
    Route::get('/casos/crear', [CasoController::class, 'create'])->name('casos.create');
    Route::post('/casos',      [CasoController::class, 'store'])->name('casos.store');

    // 3) Alimentar/Detalle (antes de show)
    Route::get('/casos/{caso}/alimentar', [DetalleCasoController::class, 'edit'])->name('detalle.edit');
    Route::post('/casos/{caso}/detalle',  [DetalleCasoController::class, 'store'])->name('detalle.store');

    // 4) PDF (antes de show y con restricción numérica)
    Route::get('/casos/{caso}/pdf', [CasoController::class, 'exportarPDF'])
        ->whereNumber('caso')
        ->name('casos.pdf');

    // 5) Mostrar caso (comodín final)
    Route::get('/casos/{caso}', [CasoController::class, 'show'])
        ->whereNumber('caso')
        ->name('casos.show');
});

// Invitados
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// /home legado
Route::get('/home', fn () => redirect()->route('casos.index'));

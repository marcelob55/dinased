<?php

// routes/web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\DetalleCasoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('casos.index'));

    Route::get('/casos',        [CasoController::class,'index'])->name('casos.index');
	
	// 1) Índice
    Route::get('/casos', [CasoController::class,'index'])->name('casos.index');

    // 2) Crear SIEMPRE antes del comodín
    Route::get('/casos/crear', [CasoController::class,'create'])->name('casos.create');
    Route::post('/casos',       [CasoController::class,'store'])->name('casos.store');

    // 3) Detalle/Alimentar
    Route::get('/casos/{caso}/alimentar', [DetalleCasoController::class,'edit'])->name('detalle.edit');
    Route::post('/casos/{caso}/detalle',  [DetalleCasoController::class,'store'])->name('detalle.store');

    // 4) Mostrar (con restricción para que NO capture “crear”)
    Route::get('/casos/{caso}', [CasoController::class,'show'])
        ->whereNumber('caso')     // o ->whereUuid('caso') si usas UUID
        ->name('casos.show');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class,'form'])->name('login');
    Route::post('/login', [AuthController::class,'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class,'logout'])->name('logout')->middleware('auth');

// Para que /home no rompa, redirige
Route::get('/home', fn() => redirect()->route('casos.index'));

Route::get('/casos/{caso}/pdf', [\App\Http\Controllers\CasoController::class, 'exportarPDF'])
    ->name('casos.pdf');

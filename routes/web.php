<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\DetalleCasoController;

Route::get('/', [CasoController::class,'index'])->name('casos.index');

# Login (sustituye login.php y admin_login.php)
Route::get('/login', [AuthController::class,'form'])->name('login.form');
Route::post('/login', [AuthController::class,'login'])->name('login.do');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

# Casos (sustituye generar_caso.php, guardar_caso.php, mis_casos.php)
Route::get('/casos', [CasoController::class,'index'])->name('casos.index');
Route::get('/casos/crear', [CasoController::class,'create'])->name('casos.create');
Route::post('/casos', [CasoController::class,'store'])->name('casos.store');
Route::get('/casos/{caso}', [CasoController::class,'show'])->name('casos.show');

# Alimentar detalle (sustituye alimentar_caso.php)
Route::get('/casos/{caso}/alimentar', [DetalleCasoController::class,'edit'])->name('detalle.edit');
Route::post('/casos/{caso}/detalle', [DetalleCasoController::class,'store'])->name('detalle.store');



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

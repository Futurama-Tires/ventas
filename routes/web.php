<?php

use App\Http\Controllers\CargaDatosController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\MaximosMinimosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Livewire\Cotizador\CotizadorLlantas;
use App\Http\Controllers\Cotizador\CotizadorLlantasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    //Obtener saludo dependiendo el día
    $hour = now()->hour;
    $greeting = match (true) {
        $hour < 12 => '¡Buenos días',
        $hour < 18 => '¡Buenas tardes',
        default => '¡Buenas noches',
    };

    return view('dashboard', compact('greeting'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Máximos y mínimos
    Route::resource('/maxmin', MaximosMinimosController::class);
    Route::resource('/factor', FactorController::class);
    Route::resource('/cargar-datos', CargaDatosController::class);
    Route::get('/descargar-maxmin', [MaximosMinimosController::class, 'descargarReporte'])->name('descargar.maxmin');

    //Cotizador
    Route::resource('/cotizador-llantas', CotizadorLlantasController::class);
});

require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BienController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\FacturaController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function (){
    Route::resource('bien', BienController::class);
});

Route::middleware(['auth', 'verified'])->group(function (){
    Route::resource('prestamo', PrestamoController::class);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



Route::get('/factura/{factura}', [FacturaController::class, 'show'])->name('factura.show');
Route::get('/factura/{factura}/descargar', [FacturaController::class, 'descargar'])->name('factura.descargar');
Route::get('/factura/{factura}/ver', [FacturaController::class, 'ver'])->name('factura.ver');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

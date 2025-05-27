<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BienController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\FacturaController;
use App\Models\Factura;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('bien', BienController::class);
    Route::resource('prestamo', PrestamoController::class);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/factura/{factura}', [FacturaController::class, 'show'])->name('factura.show');
Route::get('/factura/{factura}/ver', [FacturaController::class, 'ver'])->name('factura.ver');

// ✅ Ruta de descarga que considera tipo de factura
Route::get('/factura/{factura}/descargar', function (Factura $factura) {
    $tipo = $factura->tipo ?? 'prestamo'; // fallback para facturas viejas
    $path = "public/facturas/{$tipo}/{$factura->numero_factura}.pdf";

    if (!Storage::exists($path)) {
        abort(404, 'Factura no encontrada.');
    }

    return Storage::download($path, "{$factura->numero_factura}.pdf");
})->name('factura.descargar');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\User;
use App\Models\Prestamo;
use App\Models\Factura;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBienes = Bien::count();
        $sumaCantidad = Bien::sum('cantidad');
        $totalUsuarios = User::count(); 

        // Total de préstamos (cantidad prestada sumada de todos los bienes)
        $totalPrestamos = Prestamo::with('bienes')->get()->sum(function ($prestamo) {
            return $prestamo->bienes->sum(fn($bien) => $bien->pivot->cantidad_prestada);
        });

        // Total de devoluciones (cantidad devuelta sumada de todos los bienes)
        $totalDevoluciones = Prestamo::with('bienes')->get()->sum(function ($prestamo) {
            return $prestamo->bienes->sum(fn($bien) => $bien->pivot->cantidad_devuelta);
        });

        // Total de facturas generadas
        $totalFacturas = Factura::count();

        // Total de facturas por tipo
        $totalFacturasPrestamo = Factura::where('tipo', 'prestamo')->count();
        $totalFacturasDevolucion = Factura::where('tipo', 'devolucion')->count();

        return Inertia::render('Dashboard', [
            'totalBienes' => $totalBienes,
            'sumaCantidad' => $sumaCantidad,
            'totalUsuarios' => $totalUsuarios,
            'totalPrestamos' => $totalPrestamos,
            'totalDevoluciones' => $totalDevoluciones,
            'totalFacturas' => $totalFacturas,
            'totalFacturasPrestamo' => $totalFacturasPrestamo,
            'totalFacturasDevolucion' => $totalFacturasDevolucion,
        ]);
    }
}

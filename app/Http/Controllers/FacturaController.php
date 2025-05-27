<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function show(Factura $factura)
    {
        $factura->load('prestamo.usuario', 'prestamo.bienes');

        return view('factura.pdf', compact('factura'));
    }

    public function descargar(Factura $factura)
    {
        $factura->load('prestamo.usuario', 'prestamo.bienes');

        $pdf = Pdf::loadView('factura.pdf', compact('factura'));

        return $pdf->download("Factura-{$factura->numero_factura}.pdf");
    }

    public function ver(Factura $factura)
    {
        $factura->load('prestamo.usuario', 'prestamo.bienes');

        $pdf = Pdf::loadView('factura.pdf', compact('factura'));

        return $pdf->stream("Factura-{$factura->numero_factura}.pdf");
    }
}

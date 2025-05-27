<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Support\Str;
use App\Models\Bien;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PrestamoController extends Controller
{
    public function index()
    {
        return Inertia::render('Prestamo/Index', [
            'prestamos' => Prestamo::with([
                'usuario',
                'bienes',
                'facturas' => function ($q) {
                    $q->select('id', 'prestamo_id', 'tipo', 'numero_factura');
                }
            ])->orderBy('created_at', 'desc')->paginate(5)
        ]);
    }

    public function create()
    {
        return Inertia::render('Prestamo/Create', [
            'usuarios' => User::all(),
            'bienes' => Bien::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bienes' => 'required|array',
            'bienes.*.bien_id' => 'required|exists:biens,id',
            'bienes.*.cantidad' => 'required|integer|min:1',
        ]);

        $prestamo = null;

        try {
            DB::transaction(function () use ($request, &$prestamo) {
                $prestamo = Prestamo::create([
                    'user_id' => $request->user_id,
                ]);

                foreach ($request->bienes as $item) {
                    $bien = Bien::findOrFail($item['bien_id']);

                    if ($bien->cantidad < $item['cantidad']) {
                        throw new \Exception("No hay suficiente cantidad de {$bien->nombre}");
                    }

                    $bien->decrement('cantidad', $item['cantidad']);

                    $prestamo->bienes()->attach($bien->id, [
                        'cantidad_prestada' => $item['cantidad'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error al registrar el préstamo: ' . $e->getMessage());
        }

        if (!$prestamo) {
            return redirect()->back()->withErrors('No se pudo registrar el préstamo.');
        }

        // ✅ FACTURA DE PRÉSTAMO
        $factura = Factura::create([
            'prestamo_id' => $prestamo->id,
            'numero_factura' => 'FAC-' . strtoupper(Str::random(8)),
            'fecha_emision' => now(),
            'total' => 0,
            'tipo' => 'prestamo',
        ]);

        $pdf = Pdf::loadView('factura.pdf', [
            'factura' => $factura->load('prestamo.usuario', 'prestamo.bienes')
        ]);

        // ✅ Guardar en carpeta correspondiente
        Storage::put("public/facturas/prestamo/{$factura->numero_factura}.pdf", $pdf->output());

        return redirect()->route('prestamo.index')->with('success', 'Préstamo registrado correctamente.');
    }

    public function edit(Prestamo $prestamo)
    {
        $prestamo->load('bienes');

        return Inertia::render('Prestamo/Devolver', [
            'prestamo' => $prestamo
        ]);
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        $request->validate([
            'bienes' => 'required|array',
            'bienes.*.bien_id' => 'required|exists:biens,id',
            'bienes.*.cantidad_devuelta' => 'required|integer|min:0',
            'bienes.*.estado_devolucion' => 'required|in:completa,parcial,dañada',
        ]);

        DB::transaction(function () use ($request, $prestamo) {
            foreach ($request->bienes as $item) {
                $pivot = $prestamo->bienes()->where('bien_id', $item['bien_id'])->first()->pivot;

                $cantidadAnterior = $pivot->cantidad_devuelta ?? 0;
                $cantidadNueva = $item['cantidad_devuelta'];
                $diferencia = $cantidadNueva - $cantidadAnterior;

                if ($item['estado_devolucion'] !== 'dañada' && $diferencia > 0) {
                    $bien = Bien::findOrFail($item['bien_id']);
                    $bien->increment('cantidad', $diferencia);
                }

                $pivot->cantidad_devuelta = $cantidadNueva;
                $pivot->estado_devolucion = $item['estado_devolucion'];
                $pivot->save();
            }

            $prestamo->update(['fecha_devolucion' => now()]);

            //  FACTURA DE DEVOLUCIÓN
            $factura = Factura::create([
                'prestamo_id' => $prestamo->id,
                'numero_factura' => 'DEV-' . strtoupper(Str::random(8)),
                'fecha_emision' => now(),
                'total' => 0,
                'tipo' => 'devolucion',
            ]);

            $pdf = Pdf::loadView('factura.devolucion', [
                'factura' => $factura->load('prestamo.usuario', 'prestamo.bienes')
            ]);

            // ✅ Guardar en carpeta correspondiente
            Storage::put("public/facturas/devolucion/{$factura->numero_factura}.pdf", $pdf->output());
        });

        return redirect()->route('prestamo.index')->with('success', 'Devolución registrada y factura generada correctamente.');
    }

    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();
        return redirect()->route('prestamo.index')->with('success', 'Préstamo eliminado.');
    }
}


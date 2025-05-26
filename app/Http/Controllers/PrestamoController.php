<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PrestamoController extends Controller
{
    /**
     * Mostrar todos los préstamos.
     */
    public function index()
    {
        return Inertia::render('Prestamo/Index', [
            'prestamos' => Prestamo::with('usuario', 'bienes')->get()
        ]);
    }

    /**
     * Mostrar formulario para crear un nuevo préstamo.
     */
    public function create()
    {
        return Inertia::render('Prestamo/Create', [
            'usuarios' => User::all(),
            'bienes' => Bien::all()
        ]);
    }

    /**
     * Registrar un nuevo préstamo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bienes' => 'required|array',
            'bienes.*.bien_id' => 'required|exists:biens,id',
            'bienes.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
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

        return redirect()->route('prestamo.index')->with('success', 'Préstamo registrado correctamente.');
    }

    /**
     * Mostrar formulario para registrar devolución.
     */
    public function edit(Prestamo $prestamo)
    {
        $prestamo->load('bienes');

        return Inertia::render('Prestamo/Devolver', [
            'prestamo' => $prestamo
        ]);
    }

    /**
     * Registrar devolución de bienes.
     */
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

            // Solo sumar al inventario la diferencia entre la nueva cantidad y la anterior
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
    });

    return redirect()->route('prestamo.index')->with('success', 'Devolución registrada correctamente.');
}

    /**
     * Eliminar un préstamo (opcional).
     */
    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();
        return redirect()->route('prestamo.index')->with('success', 'Préstamo eliminado.');
    }
}


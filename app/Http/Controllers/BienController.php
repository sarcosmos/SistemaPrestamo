<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Bien;

class BienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Bien/Index',[
            'bien'=> Bien::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Bien/CreateBien');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'codigo' => 'required|numeric|min:0',
            'cantidad' => 'required|numeric|min:0',
        ]);
        Bien::create($request->all());
        return redirect()->route('bien.index');    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bien $bien)
    {
        return Inertia::render('Bien/Edit',[
            'bien'=> $bien
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bien $bien)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'codigo' => 'required|numeric|min:0',
            'cantidad' => 'required|numeric|min:0',
        ]);

        $bien->update($request->all());
        return redirect()->route('bien.index'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bien $bien)
    {
        $bien->delete();
        return Inertia::render('Bien/Index',[
            'bien'=> Bien::all()
        ]);
    }
}

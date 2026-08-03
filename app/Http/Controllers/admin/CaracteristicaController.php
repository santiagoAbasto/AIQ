<?php

namespace App\Http\Controllers\admin;

use App\Models\Caracteristica;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CaracteristicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $caracteristicas = Caracteristica::get();
        return view('admin.caracteristicas.index', compact('caracteristicas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.caracteristicas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required|string',
            'titulo' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $caracteristica = new Caracteristica;
        $caracteristica->orden = $request->orden;
        $caracteristica->titulo = $request->titulo;

        if ($request->hasFile('imagen')) {
            $caracteristica->imagen = $request->file('imagen')->store('public/caracteristicas');
        }

        $caracteristica->save();

        return redirect()->route('admin.caracteristicas.index')->with('success', 'Característica creada exitosamente.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $caracteristica = Caracteristica::findOrFail($id);
        return view('admin.caracteristicas.edit', compact('caracteristica'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $caracteristica = Caracteristica::findOrFail($id);

        $request->validate([
            'orden' => 'required|string',
            'titulo' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $caracteristica->orden = $request->orden;
        $caracteristica->titulo = $request->titulo;

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($caracteristica->imagen) {
                Storage::delete($caracteristica->imagen);
            }
            $caracteristica->imagen = $request->file('imagen')->store('public/caracteristicas');
        }

        $caracteristica->save();

        return redirect()->route('admin.caracteristicas.index')->with('success', 'Característica actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $caracteristica = Caracteristica::findOrFail($id);
        // Eliminar la imagen asociada si existe
        if ($caracteristica->imagen) {
            Storage::delete($caracteristica->imagen);
        }
        $caracteristica->delete();

        return redirect()->route('admin.caracteristicas.index')->with('success', 'Característica eliminada exitosamente.');
    }
}

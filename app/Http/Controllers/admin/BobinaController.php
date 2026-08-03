<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bobina;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BobinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bobinas = Bobina::orderBy('orden', 'asc')->get();
        return view('admin.bobinas.index', compact('bobinas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bobinas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required|string',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'descripciondos' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $bobina = new Bobina;
        $bobina->orden = $request->orden;
        $bobina->titulo = $request->titulo;
        $bobina->descripcion = $request->descripcion;
        $bobina->descripciondos = $request->descripciondos;

        if ($request->hasFile('imagen')) {
            $bobina->imagen = $request->file('imagen')->store('public/bobinas');
        }

        $bobina->save();

        return redirect()->route('admin.bobinas.index')->with('success', 'Bobina creada exitosamente.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bobina = Bobina::findOrFail($id);
        return view('admin.bobinas.edit', compact('bobina'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bobina = Bobina::findOrFail($id);

        $request->validate([
            'orden' => 'required|string',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'descripciondos' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $bobina->orden = $request->orden;
        $bobina->titulo = $request->titulo;
        $bobina->descripcion = $request->descripcion;
        $bobina->descripciondos = $request->descripciondos;

        if ($request->hasFile('imagen')) {
            $bobina->imagen = $request->file('imagen')->store('public/bobinas');
        }

        $bobina->save();
        return redirect()->route('admin.bobinas.index')->with('success', 'Bobina actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bobina = Bobina::findOrFail($id);
        if ($bobina->imagen) {
            Storage::delete($bobina->imagen);
        }
        $bobina->delete();
        return redirect()->route('admin.bobinas.index')->with('success', 'Bobina eliminada exitosamente.');
    }
}

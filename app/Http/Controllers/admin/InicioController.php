<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Inicio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inicio $inicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contenido = Inicio::find($id);
        return view('admin.inicio.editar', compact('contenido', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contenido = Inicio::find($id);
    
        if(is_null($contenido)) {
            $contenido = new Inicio();
        }
    
        if ($request->hasFile('imagen')) {
            if ($contenido->imagen && Storage::exists($contenido->imagen)) {
                Storage::delete($contenido->imagen);
            }
            $contenido->imagen = $request->file('imagen')->store('inicio', 'public');
        }

        if ($request->hasFile('imagenuna')) {
            if ($contenido->imagenuna && Storage::exists($contenido->imagenuna)) {
                Storage::delete($contenido->imagenuna);
            }
            $contenido->imagenuna = $request->file('imagenuna')->store('inicio', 'public');
        }
        if ($request->hasFile('imagendos')) {
            if ($contenido->imagendos && Storage::exists($contenido->imagendos)) {
                Storage::delete($contenido->imagendos);
            }
            $contenido->imagendos = $request->file('imagendos')->store('inicio', 'public');
        }
        if ($request->hasFile('imagentres')) {
            if ($contenido->imagentres && Storage::exists($contenido->imagentres)) {
                Storage::delete($contenido->imagentres);
            }
            $contenido->imagentres = $request->file('imagentres')->store('inicio', 'public');
        }
        if ($request->hasFile('banner')) {
            if ($contenido->banner && Storage::exists($contenido->banner)) {
                Storage::delete($contenido->banner);
            }
            $contenido->banner = $request->file('banner')->store('inicio', 'public');
        }
        if ($request->hasFile('banner_dos')) {
            if ($contenido->banner_dos && Storage::exists($contenido->banner_dos)) {
                Storage::delete($contenido->banner_dos);
            }
            $contenido->banner_dos = $request->file('banner_dos')->store('inicio', 'public');
        }
        
        
        $contenido->titulo = $request->titulo;
        $contenido->titulo_banner = $request->titulo_banner;
        $contenido->descripcion = $request->descripcion;
        $contenido->descripcion_banner = $request->descripcion_banner;
       
        $contenido->titulouno = $request->titulouno;
        $contenido->titulodos = $request->titulodos;
        $contenido->titulotres = $request->titulotres;
        $contenido->save();
    
        return redirect()->route('admin.inicio.edit', ['id' => $id])->with('success', "Registro actualizado exitosamente");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inicio $inicio)
    {
        //
    }
}

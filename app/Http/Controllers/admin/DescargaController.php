<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\Descarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DescargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $descargas = Descarga::orderBy('orden', 'asc')->get();
        return view('admin.descargas.index', compact('descargas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.descargas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $descarga = new Descarga();
        $descarga->orden = $request->input('orden');
        $descarga->titulo = $request->input('titulo');

       
       

        // que se guarde con el nombre original
        if ($request->file('pdf')) {
            $descarga->pdf = $request->file('pdf')->storeAs('public/descargas', $request->file('pdf')->getClientOriginalName());
        }


     

        $descarga->save();

        return redirect()->route('admin.descargas.index')->with('success', 'Descarga creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $descarga = Descarga::findOrFail($id);
        return view('admin.descargas.edit', compact('descarga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $descarga = Descarga::findOrFail($id);
        $descarga->orden = $request->input('orden');
        $descarga->titulo = $request->input('titulo');

      

        // Manejo del archivo PDF
        if ($request->file('pdf')) {
            $descarga->pdf = $request->file('pdf')->storeAs('public/descargas', $request->file('pdf')->getClientOriginalName());
        }

     

        $descarga->save();

        return redirect()->route('admin.descargas.index')->with('success', 'Descarga actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $descarga = Descarga::findOrFail($id);

        // Eliminar el archivo PDF si existe
        if ($descarga->pdf && \Storage::exists($descarga->pdf)) {
            \Storage::delete($descarga->pdf);
        }

        $descarga->delete();

        return redirect()->route('admin.descargas.index')->with('success', 'Descarga eliminada correctamente.');
    }
}

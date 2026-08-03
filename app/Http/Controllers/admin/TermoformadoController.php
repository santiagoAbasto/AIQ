<?php

namespace App\Http\Controllers\Admin;

use App\Models\Termoformado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TermoformadoController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $termoformado = Termoformado::findOrFail($id);
        return view('admin.termoformados.edit', compact('termoformado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $termoformado = Termoformado::findOrFail($id);
        $termoformado->descripcion = $request->input('descripcion');
        // galeria
        if ($request->hasFile('galeria')) {
            $galeria = [];
            foreach ($request->file('galeria') as $file) {
                $path = $file->store('public/termoformados');
                $galeria[] = $path;
            }
            $termoformado->galeria = json_encode($galeria);
        }
        $termoformado->save();
        return redirect()->route('admin.termoformados.edit', $id)->with('success', 'Termoformado actualizado correctamente');
    }

  
    // eliminar imagen de galeria
    public function eliminarImagen($id, $key)
        {
            $termoformado = Termoformado::findOrFail($id);

            // Manejar si galeria ya viene como array o necesita decodificarse
            $galeria = $termoformado->galeria;
            if (is_string($galeria)) {
                $galeria = json_decode($galeria, true);
            }
            
            // Verificar si la imagen existe
            if (isset($galeria[$key])) {
                // Almacenar el titulo del archivo para eliminarlo
                $imagePath = $galeria[$key];
                
                // Eliminar la imagen del almacenamiento
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                // Eliminar la imagen del array
                unset($galeria[$key]);
                
                // Reindexar array y actualizar el modelo
                $termoformado->galeria = array_values($galeria);
                $termoformado->save();

                return response()->json(['success' => true, 'message' => 'Imagen eliminada correctamente']);
            }

            return response()->json(['success' => false, 'message' => 'Imagen no encontrada']);
        }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Novedades;
use App\Models\CategoriaNovedades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NovedadesController extends Controller
{
    public function index()
    {
        $novedades = Novedades::all();
        return view('admin.novedades.index', compact('novedades'));
    }

    public function create()
    {
        return view('admin.novedades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'nullable|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif', // Añadir extensiones y tamaño máximo
            'galeria' => 'nullable|array',
            'galeria.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // Procesar la imagen principal
        $imagenPath = $request->file('imagen')->store('novedades', 'public');

        // Crear la nueva novedad
        $novedad = new Novedades();
        $novedad->orden = $request->orden;
        $novedad->titulo = $request->titulo;
        $novedad->descripcion = $request->descripcion;
        $novedad->categoria = $request->categoria ?? null;
        $novedad->imagen = $imagenPath;

        // Manejo de la carga de la galería de imágenes
        if ($request->hasFile('galeria')) {
            $galeria = [];
            foreach ($request->file('galeria') as $image) {
                $imageName = $image->getClientOriginalName();
                $imagePath = $image->storeAs('novedades', $imageName, 'public');
                $galeria[] = $imagePath;
            }
            $novedad->galeria = json_encode($galeria);
        }

        // Guardar la novedad
        $novedad->save();
        return redirect()->route('admin.novedades.index')->with('success', 'La novedad fue creada exitosamente.');
    }

    public function edit($id)
    {
        $novedad = Novedades::findOrFail($id);
        return view('admin.novedades.edit', compact('novedad'));
    }

    public function update(Request $request, $id)
    {
        $novedad = Novedades::findOrFail($id);
        $request->validate([
            'orden' => 'required|string|max:255',
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string',
            'imagen' => 'nullable|image',
            'galeria' => 'nullable|array',
            'galeria.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            if ($novedad->imagen) {
                Storage::disk('public')->delete($novedad->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('novedades', 'public');
        }

        // Manejo de la carga de la galería de imágenes
        if ($request->hasFile('galeria')) {
            $galeria = $novedad->galeria ? json_decode($novedad->galeria, true) : [];
            foreach ($request->file('galeria') as $image) {
                $imageName = $image->getClientOriginalName();
                $imagePath = $image->storeAs('novedades', $imageName, 'public');
                $galeria[] = $imagePath;
            }
            $data['galeria'] = json_encode($galeria);
        }

        $novedad->update($data);
        return redirect()->route('admin.novedades.index')->with('success', 'Novedad actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $novedad = Novedades::findOrFail($id);

        if ($novedad->imagen) {
            Storage::disk('public')->delete($novedad->imagen);
        }

        $novedad->delete();
        return redirect()->route('admin.novedades.index')->with('danger', 'Novedad eliminada exitosamente.');
    }
    
    /**
     * Eliminar una imagen específica de la galería
     */
    public function eliminarImagen($id, $key)
    {
        try {
            $novedad = Novedades::findOrFail($id);
            
            if ($novedad->galeria) {
                $galeria = json_decode($novedad->galeria, true);
                
                if (isset($galeria[$key])) {
                    // Eliminar el archivo del almacenamiento
                    Storage::disk('public')->delete($galeria[$key]);
                    
                    // Eliminar la entrada del array y reindexar
                    unset($galeria[$key]);
                    $galeria = array_values($galeria);
                    
                    // Actualizar el campo galeria en la base de datos
                    $novedad->galeria = !empty($galeria) ? json_encode($galeria) : null;
                    $novedad->save();
                    
                    return response()->json(['success' => true]);
                }
            }
            
            return response()->json(['success' => false, 'message' => 'Imagen no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

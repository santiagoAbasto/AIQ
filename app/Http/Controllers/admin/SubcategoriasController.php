<?php

namespace App\Http\Controllers\admin;

use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubcategoriasController extends Controller
{
    public function index()
    {
        $subcategorias = Subcategoria::with('categoria')->get();
        return view('admin.subcategorias.index', compact('subcategorias'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.subcategorias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:255',
            'orden'        => 'nullable|string',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug'         => 'nullable|string|unique:subcategorias,slug,NULL,id,categoria_id,' . $request->input('categoria_id'),
        ]);

        $sub = new Subcategoria;
        $sub->categoria_id = $request->categoria_id;
        $sub->orden        = $request->orden;
        $sub->titulo       = $request->titulo;
        $sub->slug         = Str::slug($request->titulo, '-');
       
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/subcategorias', $filename);
            $sub->imagen = 'subcategorias/' . $filename;
        }



        $sub->save();

        return redirect()->route('admin.subcategorias.index')->with('success', 'Subcategoría creada.');
    }

    public function edit($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        $categorias   = Categoria::all();
        return view('admin.subcategorias.edit', compact('subcategoria', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo'       => 'required|string|max:255',
            'orden'        => 'nullable|string',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug'         => 'nullable|string|unique:subcategorias,slug,' . $id . ',id,categoria_id,' . $request->input('categoria_id'),
        ]);

        $sub = Subcategoria::findOrFail($id);
        $sub->categoria_id = $request->categoria_id;
        $sub->orden        = $request->orden;
        $sub->titulo       = $request->titulo;
        $sub->slug         = Str::slug($request->titulo, '-');
      
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($sub->imagen) {
                Storage::delete('public/' . $sub->imagen);
            }
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/subcategorias', $filename);
            $sub->imagen = 'subcategorias/' . $filename;
        }   

      

        $sub->save();

        return redirect()->route('admin.subcategorias.index')->with('success', 'Subcategoría actualizada.');
    }

    public function destroy($id)
    {
        $sub = Subcategoria::findOrFail($id);
        $sub->delete();
        return redirect()->back()->with('danger', 'Subcategoría eliminada.');
    }
}

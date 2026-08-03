<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Prelacione;
use App\Models\Producto;
use App\Models\Subcategoria;
use App\Models\RelacionProducto;
use App\Models\Caracteristica;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Agregado para el slug

class ProductoController extends Controller
    {
        // Otros métodos...

        public function index()
        {
            $categorias = Categoria::all();
            $productos = Producto::with('relaciones.categoria', 'relaciones.subcategoria')->orderBy('orden', 'asc')->get(); 

            return view('admin.productos.index', compact('productos', 'categorias'));
        }

        public function create()
        {
            $productos = Producto::all();
            $categorias = Categoria::all();
            $subcategorias = Subcategoria::all();
            $caracteristicas = Caracteristica::all();
            return view('admin.productos.create', compact('productos', 'categorias', 'subcategorias', 'caracteristicas'));
        }
        
   public function store(Request $request)
{
    $request->validate([
        'orden' => 'required|string',
        'titulo' => 'required|string|max:255',
        'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        'pdf' => 'nullable|mimes:pdf,doc,docx',
        'galeria' => 'nullable|array',
        'galeria.*' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov,webm',
        'relaciones' => 'required|array|min:1',
        'relaciones.*.categoria_id' => 'required|exists:categorias,id',
        'relaciones.*.subcategoria_id' => 'nullable|exists:subcategorias,id',
        'relacionados' => 'nullable|array',
        'relacionados.*' => 'exists:productos,id',
    ]);

    $data = $request->except('galeria', 'relacionados', 'relaciones');
    $data['slug'] = Str::slug($request->input('slug', $data['titulo']));

    // pdf nombre original 

    if ($request->hasFile('pdf')) {
        $data['pdf'] = $request->file('pdf')->storeAs('productos/pdfs', $request->file('pdf')->getClientOriginalName(), 'public');
    }
    
    if ($request->hasFile('imagen')) {
        $data['imagen'] = $request->file('imagen')->storeAs('productos', $request->file('imagen')->getClientOriginalName(), 'public');
    }

    if ($request->hasFile('galeria')) {
        $galeria = [];
        foreach ($request->file('galeria') as $image) {
            $galeria[] = $image->storeAs('productos/galeria', $image->getClientOriginalName(), 'public');
        }
        $data['galeria'] = json_encode($galeria);
    }

    // destacado
        $data['destacado'] = $request->has('destacado') ? 1 : 0;
    // Crear el producto
    $producto = Producto::create($data);

    // Guardar relaciones categoria/subcategoria en relacion_productos
    foreach ($request->input('relaciones', []) as $relacion) {
        RelacionProducto::create([
            'producto_id'     => $producto->id,
            'categoria_id'    => $relacion['categoria_id'],
            'subcategoria_id' => $relacion['subcategoria_id'] ?? null,
        ]);
    }

    // Guardar productos relacionados
    if ($request->has('relacionados')) {
        $producto->relacionados()->sync($request->input('relacionados'));
    }

    return redirect()->route('admin.productos.index')->with('success', 'Producto creado y código QR generado.');
}


        public function edit($id)
        {
            $producto = Producto::findOrFail($id);
            $categorias = Categoria::all();
            $subcategorias = Subcategoria::all();
            $caracteristicas = Caracteristica::all();
            // Obtener todos los productos menos el actual para evitar relacionarse a sí mismo
            $productos = Producto::where('id', '!=', $id)->get();
            
            return view('admin.productos.edit', compact('producto', 'categorias', 'subcategorias', 'productos', 'caracteristicas'));
        }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'orden' => 'required|string',
            'titulo' => 'required|string|max:255',
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'pdf' => 'nullable|mimes:pdf,doc,docx',
            'galeria' => 'nullable|array',
            'galeria.*' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov',
            'relaciones' => 'required|array|min:1',
            'relaciones.*.categoria_id' => 'required|exists:categorias,id',
            'relaciones.*.subcategoria_id' => 'nullable|exists:subcategorias,id',
            'relacionados' => 'nullable|array',
            'relacionados.*' => 'exists:productos,id',
        ]);

        $data = $request->except('galeria', 'relacionados', 'relaciones');
        $data['slug'] = Str::slug($request->input('slug', $data['titulo']));

  

    if ($request->hasFile('pdf')) {
        if ($producto->pdf) Storage::disk('public')->delete($producto->pdf);
        $data['pdf'] = $request->file('pdf')->storeAs('productos/pdfs', $request->file('pdf')->getClientOriginalName(), 'public');
    }

    if ($request->hasFile('imagen')) {
        if ($producto->imagen) Storage::disk('public')->delete($producto->imagen);
        $data['imagen'] = $request->file('imagen')->storeAs('productos', $request->file('imagen')->getClientOriginalName(), 'public');
    }

    if ($request->hasFile('galeria')) {
        $galeriaActual = $producto->galeria;
        if (is_string($galeriaActual)) {
            $galeriaActual = json_decode($galeriaActual, true);
        }
        if (!is_array($galeriaActual)) {
            $galeriaActual = [];
        }

        foreach ($request->file('galeria') as $image) {
            $galeriaActual[] = $image->storeAs('productos/galeria', $image->getClientOriginalName(), 'public');
        }
        $data['galeria'] = json_encode($galeriaActual);
    }
    $data['destacado'] = $request->has('destacado') ? 1 : 0;
    $producto->update($data);

    // Sincronizar relaciones categoria/subcategoria
    RelacionProducto::where('producto_id', $producto->id)->delete();
    foreach ($request->input('relaciones', []) as $relacion) {
        RelacionProducto::create([
            'producto_id'     => $producto->id,
            'categoria_id'    => $relacion['categoria_id'],
            'subcategoria_id' => $relacion['subcategoria_id'] ?? null,
        ]);
    }

    // Sincronizar productos relacionados (si array está vacío, elimina relaciones)
    $producto->relacionados()->sync($request->input('relacionados', []));

    return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado y QR regenerado.');
}


        public function destroy($id)
        {
            $producto = Producto::find($id);
            $producto->delete();
            return redirect()->route('admin.productos.index')->with('danger', 'Producto eliminada exitosamente.');
        }

    public function eliminarImagen($id, $key)
        {
            $producto = Producto::findOrFail($id);

            // Manejar si galeria ya viene como array o necesita decodificarse
            $galeria = $producto->galeria;
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
                $producto->galeria = array_values($galeria);
                $producto->save();

                return response()->json(['success' => true, 'message' => 'Imagen eliminada correctamente']);
            }

            return response()->json(['success' => false, 'message' => 'Imagen no encontrada']);
        }

   
    }


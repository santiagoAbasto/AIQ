<?php


namespace App\Http\Controllers\admin;
use App\Models\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Agregado para el slug


class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::get();
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        return view('admin.categorias.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categorias = new Categoria;
        $categorias->orden = $request->orden;
        $categorias->titulo = $request->titulo;
        //slug
        $categorias->slug = Str::slug($request->titulo, '-');

       
        if($request->file('imagen')){
            $categorias->imagen = $request->file('imagen')->store('public/categorias');
            }
        
        if(isset($request->destacado))
        $categorias->destacado    = 1;
        else
        $categorias->destacado    = 0;
        
        $categorias->save();


        return redirect()->route('admin.categorias.index')->with('success', 'La categorias fue creada');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categorias  $categorias
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categorias = Categoria::find($id);

        return view('admin.categorias.edit', compact('categorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categorias  $categorias
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $categorias = categoria::find($id);    
        $categorias->orden     = $request->orden;
        $categorias->titulo = $request->titulo;
        $categorias->slug = Str::slug($request->titulo, '-');
      
        if($request->file('imagen')){
            $categorias->imagen = $request->file('imagen')->store('public/categorias');
            }
       
            if(isset($request->destacado))
            $categorias->destacado    = 1;
            else
            $categorias->destacado    = 0;
            
            $categorias->save();
        return redirect()->route('admin.categorias.index')->with('success', "Registro actualizado exitósamente" );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categorias  $categorias
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categorias = Categoria::find($id); 
        $categorias->delete();
        return redirect()->back()->with('danger', "Registro eliminado exitósamente" ); 
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index($seccion)
    {
        $slider = Slider::where('seccion', $seccion)->orderBy('orden', 'asc')->get();
        return view('admin.slider.index', compact('slider', 'seccion'));
    }

    public function create($seccion)
    {
        return view('admin.slider.create', compact('seccion'));
    }

    public function store(Request $request, $seccion)
    {
        $slider = new Slider;
        $slider->orden = $request->orden;
        $slider->titulo = $request->titulo;
        $slider->descripcion = $request->descripcion;
        // boolean activo
        // $slider->activo = $request->activo ? true : false; // Asegur
        $slider->seccion = $seccion;

        if ($request->hasFile('imagen')) {
            $filename = $request->file('imagen')->getClientOriginalName();
            $path = $request->file('imagen')->storeAs('public/sliders', $filename);
            $slider->imagen = $path;
        }

        $slider->save();

        return redirect()->route('admin.slider.index', $seccion)->with('success', 'El slider fue creado');
    }

    public function edit($seccion, $id)
    {
        $slider = Slider::find($id);

        if (!$slider) {
            return redirect()->route('slider.index', $seccion)->with('error', 'Registro no encontrado.');
        }

        return view('admin.slider.editar', compact('slider', 'seccion'));
    }

    public function update(Request $request, $seccion, $id)
    {
        $slider = Slider::find($id);

        if (!$slider) {
            return redirect()->route('adminslider.index', $seccion)->with('error', 'Registro no encontrado.');
        }

        if ($request->hasFile('imagen')) {
            Storage::delete($slider->imagen);
            $filename = $request->file('imagen')->getClientOriginalName();
            $path = $request->file('imagen')->storeAs('public/sliders', $filename);
        } else {
            $path = $slider->imagen;
        }

        $slider->orden = $request->orden;
        $slider->titulo = $request->titulo;
        $slider->descripcion = $request->descripcion;
        // $slider->activo = $request->activo ? true : false; // Asegur
        $slider->imagen = $path;
        $slider->seccion = $seccion;
        $slider->save();

        return redirect()->route('admin.slider.index', $seccion)->with('success', 'Registro actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $slider = Slider::find($id);

        if (!$slider) {
            return redirect()->back()->with('error', 'Registro no encontrado.');
        }

        Storage::delete($slider->imagen);
        $slider->delete();

        return redirect()->back()->with('danger', 'Registro eliminado exitosamente.');
    }
}

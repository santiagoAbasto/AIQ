<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Logo;

class LogoController extends Controller
{
    public function edit($id)
    {
        $logo = Logo::findOrFail($id);
        return view('admin.logos.edit', compact('logo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'logo_header' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_headerdos	'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_footer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $logo = Logo::findOrFail($id);
    
        // Procesar y guardar la imagen del logo_header
        if ($request->file('logo_header')) {
            // Almacenar la imagen con su nombre original en el directorio 'public/clientes'
            $logo->logo_header = $request->file('logo_header')->storeAs('public/logos', $request->file('logo_header')->getClientOriginalName());
        }
        if ($request->file('logo_headerdos	')) {
            // Almacenar la imagen con su nombre original en el directorio 'public/clientes'
            $logo->logo_headerdos	 = $request->file('logo_headerdos	')->storeAs('public/logos', $request->file('logo_headerdos	')->getClientOriginalName());
        }
        // Procesar y guardar la imagen del logo_header
        if ($request->file('logo_footer')) {
            // Almacenar la imagen con su nombre original en el directorio 'public/clientes'
            $logo->logo_footer = $request->file('logo_footer')->storeAs('public/logos', $request->file('logo_footer')->getClientOriginalName());
        }
    
        $logo->save();
    
        return redirect()->route('admin.logos.edit', $id)->with('success', 'Logo actualizado exitosamente.');
    }
    
    
}

<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\ContenidoBobina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContenidoBobinaController extends Controller
{
   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contenidoBobina = ContenidoBobina::findOrFail($id);
        return view('admin.contenido_bobina.edit', compact('contenidoBobina'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contenidoBobina = ContenidoBobina::findOrFail($id);
        $contenidoBobina->update($request->all());

        return redirect()->route('admin.contenido_bobina.edit', $contenidoBobina->id)
                         ->with('success', 'Contenido de Bobina actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contenidoBobina = ContenidoBobina::findOrFail($id);
        $contenidoBobina->delete();

        return redirect()->route('admin.contenido_bobina.index')
                         ->with('success', 'Contenido de Bobina eliminado exitosamente.');
    }
}

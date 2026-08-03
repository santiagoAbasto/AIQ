<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactoController extends Controller
{
    public function edit($id)
    {
        $contacto = Contacto::findOrFail($id);
        return view('admin.contacto.edit', compact('contacto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // Validaciones aquí
        ]);

        $contacto = Contacto::findOrFail($id);
        $contacto->update($request->all());

        return redirect()->route('admin.contacto.edit', $id)->with('success', 'Contacto actualizado exitosamente.');
    }
}

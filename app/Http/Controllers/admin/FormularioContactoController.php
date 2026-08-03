<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FormularioContacto; // Importar el modelo FormularioContacto

class FormularioContactoController extends Controller
{
    public function index()
    {
        $mensajes = FormularioContacto::orderBy('created_at', 'desc')->get();
        return view('admin.contacto.contactomensaje', compact('mensajes'));
    }

    public function show($id)
    {
        $mensaje = FormularioContacto::findOrFail($id);
        return view('admin.contacto.show', compact('mensaje'));
    }

    public function destroy($id)
    {
        $mensaje = FormularioContacto::findOrFail($id);
        $mensaje->delete();
        
        return redirect()->route('admin.contactomensaje.index')
            ->with('success', 'El mensaje ha sido eliminado exitosamente');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginclienteAuthController extends Controller
{
      /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        
        if (!Auth::guard('logincliente')->attempt($credentials)) {
            return back()->withErrors(['msj' => "Las credenciales no coinciden con nuestros registros."]);
        }
    
        // Obtener el usuario autenticado
        $user = Auth::guard('logincliente')->user();
    
        // Verificar el estado del usuario
        if ($user->estado != 1) {
            // Cerrar sesión si el estado no es 1
            Auth::guard('logincliente')->logout();
            return redirect()->back()->withErrors(['msj' => "Tu cuenta está inactiva. Contacta al administrador."]);
        }
    
        // Regenerar la sesión
        $request->session()->regenerate();
    
        // Redireccionar a la ruta del carrito si el estado es 1
        return redirect()->route('cart.index')->with('success', '¡Inicio de sesión exitoso!');
    }
    

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('logincliente')->logout();

      //  $request->session()->invalidate();
      //  $request->session()->regenerateToken();

      return redirect()->back()->with('danger', '¡Sesión Cerrada!');
    }
}

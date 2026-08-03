<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Logincliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteAuthController extends Controller
{
    public function csrfToken(Request $request): JsonResponse
    {
        $request->session()->regenerateToken();

        return response()
            ->json(['token' => csrf_token()])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, private')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function landing(): RedirectResponse
    {
        if (Auth::guard('logincliente')->check()) {
            return redirect()->route('cliente.dashboard');
        }

        return redirect()->route('cliente.login');
    }

    public function showLogin(): RedirectResponse
    {
        if (Auth::guard('logincliente')->check()) {
            return redirect()->route('cliente.dashboard');
        }

        return redirect()->route('index')->with('cliente_auth_modal', 'login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $referer = $request->headers->get('referer', route('index'));

        if (! Auth::guard('logincliente')->attempt($credentials, $request->boolean('remember'))) {
            return redirect($referer)
                ->withInput($request->only('email'))
                ->with('error', 'Las credenciales no coinciden con nuestros registros.')
                ->with('cliente_auth_modal', 'login');
        }

        $cliente = Auth::guard('logincliente')->user();

        if (! $cliente->hasValidAccess()) {
            Auth::guard('logincliente')->logout();

            return redirect($referer)
                ->withInput($request->only('email'))
                ->with('error', $cliente->is_enabled ? 'Tu acceso está vencido.' : 'Tu cuenta está pendiente de aprobación.')
                ->with('cliente_auth_modal', 'login');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('cliente.dashboard'));
    }

    public function showRegister(): RedirectResponse
    {
        if (Auth::guard('logincliente')->check()) {
            return redirect()->route('cliente.dashboard');
        }

        return redirect()->route('index')->with('cliente_auth_modal', 'register');
    }

    public function register(Request $request): RedirectResponse
    {
        $referer = $request->headers->get('referer', route('index'));

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:loginclientes,email'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Logincliente::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'is_enabled' => false,
            'access_unlimited' => false,
        ]);

        return redirect($referer)
            ->with('success', 'Tu solicitud fue recibida. Un administrador debe habilitar el acceso.')
            ->with('cliente_auth_modal', 'login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('logincliente')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with('success', 'Sesión cerrada.');
    }

}

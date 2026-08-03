<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginclienteAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('logincliente')->check()) {
            return redirect()->route('cliente.login');
        }

        $cliente = Auth::guard('logincliente')->user();

        if (! $cliente->hasValidAccess()) {
            Auth::guard('logincliente')->logout();

            return redirect()
                ->route('cliente.login')
                ->with('error', 'Tu acceso a Zona Clientes no está vigente. Contactá al administrador.');
        }

        return $next($request);
    }
}

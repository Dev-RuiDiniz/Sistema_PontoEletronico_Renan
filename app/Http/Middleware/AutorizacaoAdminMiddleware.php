<?php

namespace App\Http\Middleware;

use App\Usuario;
use Closure;
use Illuminate\Support\Facades\Session;

class AutorizacaoAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        $usuario = Usuario::find(Session::get('login.ponto.usuario_id'));

        if (!$usuario || $usuario->ativo != 1) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }

        if ($usuario->admin != 1) {
            return redirect('/dashboard')->withErrors([
                'acesso' => 'Esta área está disponível somente para administradores.',
            ]);
        }

        return $next($request);
    }
}

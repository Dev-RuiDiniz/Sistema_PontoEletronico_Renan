<?php

namespace App\Http\Middleware;

use Closure;
use App\Usuario;
use Illuminate\Support\Facades\Session;

class AutorizacaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $usuario = Usuario::find(Session::get('login.ponto.usuario_id'));

        if (!$usuario || $usuario->ativo != 1) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }

        if ($usuario->admin == 1) {
            return redirect('/admin');
        }

        return $next($request);
    }
}

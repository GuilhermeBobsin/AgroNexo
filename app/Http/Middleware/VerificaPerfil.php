<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificaPerfil
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$perfis): Response
    {
        $user = $request->user();

        if ($user && $user->status !== 'ativo') {
            abort(403, 'Seu acesso está inativo. Entre em contato com um administrador.');
        }

        if (! $user) {
            abort(401);
        }

        if (! in_array($user->perfil, ['admin', 'agronomo', 'operador'], true)) {
            abort(403, 'O perfil da conta não é válido. Contate um administrador.');
        }

        if (! in_array($user->perfil, $perfis, true)) {
            $perfilAtual = $user->perfil;

            return redirect()
                ->route('dashboard.' . $perfilAtual)
                ->with('erro', 'Você não tem acesso a essa área.');
        }

        return $next($request);
    }
}

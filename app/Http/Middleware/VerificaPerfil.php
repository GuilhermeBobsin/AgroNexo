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

        if (! $user || ! in_array($user->perfil, $perfis, true)) {
            $perfilAtual = $user?->perfil ?? 'operador';

            return redirect()
                ->route('dashboard.' . $perfilAtual)
                ->with('erro', 'Você não tem acesso a essa área.');
        }

        return $next($request);
    }
}

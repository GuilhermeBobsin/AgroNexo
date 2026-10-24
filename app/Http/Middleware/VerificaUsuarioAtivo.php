<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificaUsuarioAtivo
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->status !== 'ativo') {
            abort(403, 'Seu acesso está inativo. Entre em contato com um administrador.');
        }

        return $next($request);
    }
}

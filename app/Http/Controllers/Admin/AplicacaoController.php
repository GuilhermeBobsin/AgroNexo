<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aplicacao;
use App\Models\Propriedade;
use Illuminate\Http\Request;

class AplicacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Aplicacao::with(['talhao.propriedade', 'talhao.cultura', 'produto', 'usuario', 'tarefa'])
            ->latest('data_aplicacao')->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('propriedade_id')) {
            $query->whereHas('talhao.propriedade', fn ($q) => $q->whereKey($request->integer('propriedade_id')));
        }

        $aplicacoes = $query->paginate(20)->withQueryString();
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);
        return view('admin.aplicacoes.index', compact('aplicacoes', 'propriedades'));
    }

    public function show(Aplicacao $aplicacao)
    {
        $aplicacao->load(['talhao.propriedade', 'talhao.cultura', 'produto', 'usuario', 'tarefa']);
        return view('admin.aplicacoes.show', compact('aplicacao'));
    }
}

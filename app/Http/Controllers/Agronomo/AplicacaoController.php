<?php

namespace App\Http\Controllers\Agronomo;

use App\Http\Controllers\Controller;
use App\Models\Aplicacao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AplicacaoController extends Controller
{
    public function index(Request $request)
    {
        $propriedades = auth()->user()->propriedades()->orderBy('nome')->get(['propriedades.id', 'nome']);
        $propriedadeIds = $propriedades->modelKeys();
        $dataFimRules = ['nullable', 'date'];
        if ($request->filled('data_inicio')) {
            $dataFimRules[] = 'after_or_equal:data_inicio';
        }
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['realizada', 'planejada', 'cancelada'])],
            'propriedade_id' => ['nullable', 'integer', Rule::in($propriedadeIds)],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => $dataFimRules,
        ]);

        $query = Aplicacao::with(['talhao.propriedade', 'talhao.cultura', 'produto', 'usuario'])
            ->whereHas('talhao', fn ($talhoes) => $talhoes->whereIn('propriedade_id', $propriedadeIds))
            ->latest('data_aplicacao')
            ->latest('id');

        if (!empty($validated['propriedade_id'])) {
            $query->whereHas('talhao', fn ($talhoes) => $talhoes->where('propriedade_id', $validated['propriedade_id']));
        }
        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }
        if (!empty($validated['data_inicio'])) {
            $query->whereDate('data_aplicacao', '>=', $validated['data_inicio']);
        }
        if (!empty($validated['data_fim'])) {
            $query->whereDate('data_aplicacao', '<=', $validated['data_fim']);
        }

        $aplicacoes = $query->paginate(15)->withQueryString();

        return view('agronomo.aplicacoes.index', compact('aplicacoes', 'propriedades'));
    }

    public function show(Aplicacao $aplicacao)
    {
        abort_unless(auth()->user()->propriedades()->whereKey($aplicacao->talhao()->value('propriedade_id'))->exists(), 404);

        $aplicacao->load(['talhao.propriedade', 'talhao.cultura', 'produto', 'usuario', 'tarefa']);

        return view('agronomo.aplicacoes.show', compact('aplicacao'));
    }
}

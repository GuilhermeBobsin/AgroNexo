<?php

namespace App\Http\Controllers\Agronomo;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TarefaController extends Controller
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
            'status' => ['nullable', Rule::in(['pendente', 'em_andamento', 'concluida', 'cancelada'])],
            'tipo' => ['nullable', Rule::in(['aplicacao', 'aracao', 'calagem', 'irrigacao', 'manutencao', 'outro'])],
            'propriedade_id' => ['nullable', 'integer', Rule::in($propriedadeIds)],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => $dataFimRules,
        ]);

        $query = Tarefa::with(['propriedade', 'talhao', 'responsavel', 'recurso'])
            ->whereIn('propriedade_id', $propriedadeIds)
            ->orderBy('data_prevista')
            ->orderBy('hora_prevista');

        foreach (['status', 'tipo', 'propriedade_id'] as $filtro) {
            if (!empty($validated[$filtro])) {
                $query->where($filtro, $validated[$filtro]);
            }
        }
        if (!empty($validated['data_inicio'])) {
            $query->whereDate('data_prevista', '>=', $validated['data_inicio']);
        }
        if (!empty($validated['data_fim'])) {
            $query->whereDate('data_prevista', '<=', $validated['data_fim']);
        }

        $tarefas = $query->paginate(15)->withQueryString();

        return view('agronomo.tarefas.index', compact('tarefas', 'propriedades'));
    }

    public function show(Tarefa $tarefa)
    {
        abort_unless(auth()->user()->propriedades()->whereKey($tarefa->propriedade_id)->exists(), 404);

        $tarefa->load(['propriedade', 'talhao.cultura', 'responsavel', 'recurso', 'produto', 'aplicacao']);

        return view('agronomo.tarefas.show', compact('tarefa'));
    }
}

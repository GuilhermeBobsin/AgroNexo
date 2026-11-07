<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use App\Models\Aplicacao;
use App\Models\Propriedade;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $propriedades = Propriedade::orderBy('nome')->get();
        $operadores = User::where('perfil', 'operador')->orderBy('name')->get();

        $propriedadeId = $request->input('propriedade_id');
        $responsavelId = $request->input('responsavel_id');
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');

        $tarefasQuery = Tarefa::query()
            ->when($propriedadeId, fn ($q, $v) => $q->where('propriedade_id', $v))
            ->when($responsavelId, fn ($q, $v) => $q->where('responsavel_id', $v))
            ->when($dataInicio, fn ($q, $v) => $q->whereDate('data_prevista', '>=', $v))
            ->when($dataFim, fn ($q, $v) => $q->whereDate('data_prevista', '<=', $v));

        $indicadores = [
            'tarefas' => (clone $tarefasQuery)->count(),
            'pendentes' => (clone $tarefasQuery)->where('status', 'pendente')->count(),
            'em_andamento' => (clone $tarefasQuery)->where('status', 'em_andamento')->count(),
            'concluidas' => (clone $tarefasQuery)->where('status', 'concluida')->count(),
            'aplicacoes' => (clone $tarefasQuery)->where('tipo', 'aplicacao')->where('status', 'concluida')->count(),
        ];

        $tarefas = (clone $tarefasQuery)
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->with(['propriedade', 'talhao', 'responsavel'])
            ->orderBy('data_prevista')
            ->limit(8)
            ->get();

        $aplicacoesQuery = Aplicacao::query()
            ->when($propriedadeId, fn ($q, $v) => $q->whereHas('talhao', fn ($t) => $t->where('propriedade_id', $v)))
            ->when($responsavelId, fn ($q, $v) => $q->where('usuario_id', $v))
            ->when($dataInicio, fn ($q, $v) => $q->whereDate('data_aplicacao', '>=', $v))
            ->when($dataFim, fn ($q, $v) => $q->whereDate('data_aplicacao', '<=', $v));

        $aplicacoes = (clone $aplicacoesQuery)
            ->with(['produto', 'talhao.propriedade', 'usuario'])
            ->latest('data_aplicacao')
            ->limit(8)
            ->get();

        $resumoPropriedades = Propriedade::orderBy('nome')->get()->map(function (Propriedade $propriedade) {
            $base = Tarefa::where('propriedade_id', $propriedade->id);

            $propriedade->resumo = [
                'tarefas' => (clone $base)->count(),
                'pendentes' => (clone $base)->where('status', 'pendente')->count(),
                'em_andamento' => (clone $base)->where('status', 'em_andamento')->count(),
                'concluidas' => (clone $base)->where('status', 'concluida')->count(),
                'aplicacoes' => (clone $base)->where('tipo', 'aplicacao')->where('status', 'concluida')->count(),
            ];

            return $propriedade;
        });

        $recomendacoesPendentes = Alerta::where('status', 'ativo')->count();

        return view('admin.dashboard', compact(
            'propriedades', 'operadores', 'indicadores', 'tarefas',
            'aplicacoes', 'resumoPropriedades', 'recomendacoesPendentes'
        ));
    }
}
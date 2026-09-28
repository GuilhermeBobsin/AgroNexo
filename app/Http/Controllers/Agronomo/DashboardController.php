<?php

namespace App\Http\Controllers\Agronomo;

use App\Http\Controllers\Controller;
use App\Models\Aplicacao;
use App\Models\Talhao;
use App\Models\Tarefa;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();
        $propriedades = $usuario->propriedades()
            ->withCount(['talhoes', 'tarefas'])
            ->orderBy('nome')
            ->get();
        $propriedadeIds = $propriedades->modelKeys();

        $tarefas = Tarefa::whereIn('propriedade_id', $propriedadeIds);
        $inicioDoMes = now()->startOfMonth();

        $indicadores = [
            'propriedades' => $propriedades->count(),
            'talhoes' => Talhao::whereIn('propriedade_id', $propriedadeIds)->count(),
            'area' => Talhao::whereIn('propriedade_id', $propriedadeIds)->sum('area'),
            'tarefas_pendentes' => (clone $tarefas)->where('status', 'pendente')->count(),
            'tarefas_em_andamento' => (clone $tarefas)->where('status', 'em_andamento')->count(),
            'tarefas_concluidas_mes' => (clone $tarefas)
                ->where('status', 'concluida')
                ->where('data_conclusao', '>=', $inicioDoMes)
                ->count(),
            'aplicacoes_mes' => Aplicacao::whereHas('talhao', fn ($query) => $query->whereIn('propriedade_id', $propriedadeIds))
                ->where('status', 'realizada')
                ->whereDate('data_aplicacao', '>=', $inicioDoMes->toDateString())
                ->count(),
        ];

        $proximasTarefas = Tarefa::with(['propriedade', 'talhao', 'responsavel'])
            ->whereIn('propriedade_id', $propriedadeIds)
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->orderBy('data_prevista')
            ->orderBy('hora_prevista')
            ->limit(6)
            ->get();

        $aplicacoesRecentes = Aplicacao::with(['talhao.propriedade', 'talhao.cultura', 'produto', 'usuario'])
            ->whereHas('talhao', fn ($query) => $query->whereIn('propriedade_id', $propriedadeIds))
            ->latest('data_aplicacao')
            ->latest('id')
            ->limit(6)
            ->get();

        return view('agronomo.dashboard', compact(
            'usuario',
            'propriedades',
            'indicadores',
            'proximasTarefas',
            'aplicacoesRecentes'
        ));
    }
}

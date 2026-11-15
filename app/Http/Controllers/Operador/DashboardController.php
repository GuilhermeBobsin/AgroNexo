<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;

class DashboardController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::where('responsavel_id', auth()->id())
            ->whereHas('propriedade.usuarios', fn ($query) => $query->where('users.id', auth()->id()));
        $indicadores = [
            'pendentes' => (clone $tarefas)->where('status', 'pendente')->count(),
            'em_andamento' => (clone $tarefas)->where('status', 'em_andamento')->count(),
            'concluidas_mes' => (clone $tarefas)->where('status', 'concluida')->where('data_conclusao', '>=', now()->startOfMonth())->count(),
            'total' => (clone $tarefas)->count(),
            'atrasadas' => (clone $tarefas)->whereIn('status', ['pendente', 'em_andamento'])->whereDate('data_prevista', '<', today())->count(),
        ];
        $proximasTarefas = (clone $tarefas)->with(['propriedade', 'talhao', 'produto'])
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->orderBy('data_prevista')->orderBy('hora_prevista')->limit(10)->get();

        return view('operador.dashboard', compact('indicadores', 'proximasTarefas'));
    }
}

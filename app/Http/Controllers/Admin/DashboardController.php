<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aplicacao;
use App\Models\Propriedade;
use App\Models\Recomendacao;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'propriedade_id' => ['nullable', 'integer', 'exists:propriedades,id'],
            'responsavel_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('perfil', 'operador')],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
        ]);

        $tarefasQuery = Tarefa::query();
        $aplicacoesQuery = Aplicacao::query();
        if (!empty($validated['propriedade_id'])) {
            $tarefasQuery->where('propriedade_id', $validated['propriedade_id']);
            $aplicacoesQuery->whereHas('talhao', fn ($query) => $query->where('propriedade_id', $validated['propriedade_id']));
        }
        if (!empty($validated['responsavel_id'])) {
            $tarefasQuery->where('responsavel_id', $validated['responsavel_id']);
            $aplicacoesQuery->where('usuario_id', $validated['responsavel_id']);
        }
        if (!empty($validated['data_inicio'])) {
            $tarefasQuery->whereDate('data_prevista', '>=', $validated['data_inicio']);
            $aplicacoesQuery->whereDate('data_aplicacao', '>=', $validated['data_inicio']);
        }
        if (!empty($validated['data_fim'])) {
            $tarefasQuery->whereDate('data_prevista', '<=', $validated['data_fim']);
            $aplicacoesQuery->whereDate('data_aplicacao', '<=', $validated['data_fim']);
        }

        $indicadores = [
            'tarefas' => (clone $tarefasQuery)->count(),
            'pendentes' => (clone $tarefasQuery)->where('status', 'pendente')->count(),
            'em_andamento' => (clone $tarefasQuery)->where('status', 'em_andamento')->count(),
            'concluidas' => (clone $tarefasQuery)->where('status', 'concluida')->count(),
            'aplicacoes' => (clone $aplicacoesQuery)->where('status', 'realizada')->count(),
        ];
        $recomendacoesPendentes = Recomendacao::where('status', 'pendente')->count();

        $tarefas = (clone $tarefasQuery)->with(['propriedade', 'talhao', 'responsavel'])
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->orderBy('data_prevista')->orderBy('hora_prevista')->limit(8)->get();
        $aplicacoes = (clone $aplicacoesQuery)->with(['talhao.propriedade', 'produto', 'usuario'])
            ->latest('data_aplicacao')->latest('id')->limit(6)->get();
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);
        $operadores = User::where('perfil', 'operador')->where('status', 'ativo')->orderBy('name')->get(['id', 'name']);
        $tarefasPorPropriedade = (clone $tarefasQuery)
            ->select('propriedade_id')
            ->selectRaw("COUNT(*) as total, SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes, SUM(CASE WHEN status = 'em_andamento' THEN 1 ELSE 0 END) as em_andamento, SUM(CASE WHEN status = 'concluida' THEN 1 ELSE 0 END) as concluidas")
            ->groupBy('propriedade_id')->get()->keyBy('propriedade_id');
        $aplicacoesPorPropriedade = DB::table('aplicacoes')
            ->join('talhoes', 'talhoes.id', '=', 'aplicacoes.talhao_id')
            ->select('talhoes.propriedade_id')
            ->selectRaw("COUNT(*) as total")
            ->where('aplicacoes.status', 'realizada')
            ->when(!empty($validated['propriedade_id']), fn ($query) => $query->where('talhoes.propriedade_id', $validated['propriedade_id']))
            ->when(!empty($validated['responsavel_id']), fn ($query) => $query->where('aplicacoes.usuario_id', $validated['responsavel_id']))
            ->when(!empty($validated['data_inicio']), fn ($query) => $query->whereDate('aplicacoes.data_aplicacao', '>=', $validated['data_inicio']))
            ->when(!empty($validated['data_fim']), fn ($query) => $query->whereDate('aplicacoes.data_aplicacao', '<=', $validated['data_fim']))
            ->groupBy('talhoes.propriedade_id')->get()->keyBy('propriedade_id');
        $resumoPropriedades = $propriedades->map(function ($propriedade) use ($tarefasPorPropriedade, $aplicacoesPorPropriedade) {
            $tarefas = $tarefasPorPropriedade->get($propriedade->id);
            $propriedade->resumo = [
                'tarefas' => (int) ($tarefas->total ?? 0),
                'pendentes' => (int) ($tarefas->pendentes ?? 0),
                'em_andamento' => (int) ($tarefas->em_andamento ?? 0),
                'concluidas' => (int) ($tarefas->concluidas ?? 0),
                'aplicacoes' => (int) ($aplicacoesPorPropriedade->get($propriedade->id)->total ?? 0),
            ];
            return $propriedade;
        });

        return view('admin.dashboard', compact('indicadores', 'recomendacoesPendentes', 'tarefas', 'aplicacoes', 'propriedades', 'operadores', 'resumoPropriedades'));
    }
}

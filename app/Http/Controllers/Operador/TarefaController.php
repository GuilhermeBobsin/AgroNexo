<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Recurso;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarefa::with(['propriedade', 'talhao'])
            ->where('responsavel_id', auth()->id())
            ->orderBy('data_prevista');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tarefas = $query->paginate(15)->withQueryString();

        return view('operador.tarefas.index', compact('tarefas'));
    }

    public function show(Tarefa $tarefa)
    {
        abort_unless($tarefa->responsavel_id === auth()->id(), 403);

        $tarefa->load(['propriedade', 'talhao', 'recurso', 'produto']);

        return view('operador.tarefas.show', compact('tarefa'));
    }

    public function atualizarStatus(Request $request, Tarefa $tarefa)
    {
        abort_unless($tarefa->responsavel_id === auth()->id(), 403);

        $validated = $request->validate(['status' => 'required|in:em_andamento,concluida,cancelada']);

        DB::transaction(function () use ($tarefa, $validated) {
            $tarefa = Tarefa::whereKey($tarefa->id)->lockForUpdate()->firstOrFail();
            $novoStatus = $validated['status'];
            abort_unless(
                ($tarefa->status === 'pendente' && $novoStatus === 'em_andamento') ||
                ($tarefa->status === 'em_andamento' && in_array($novoStatus, ['concluida', 'cancelada'], true)),
                422,
                'A tarefa não pode mudar para esse status.'
            );

            $tarefa->status = $novoStatus;
            $tarefa->data_conclusao = $novoStatus === 'concluida' ? now() : null;
            $tarefa->save();

            if ($tarefa->recurso_id) {
                $recurso = Recurso::whereKey($tarefa->recurso_id)->lockForUpdate()->first();
                if ($recurso) {
                    if ($novoStatus === 'em_andamento') {
                        abort_unless($recurso->status === 'disponivel', 422, 'O recurso associado não está disponível.');
                        $recurso->status = 'em_uso';
                        $recurso->save();
                    } else {
                        $outraTarefaEmAndamento = Tarefa::where('recurso_id', $recurso->id)
                            ->where('id', '!=', $tarefa->id)
                            ->where('status', 'em_andamento')
                            ->exists();
                        if (!$outraTarefaEmAndamento) {
                            $recurso->status = 'disponivel';
                            $recurso->save();
                        }
                    }
                }
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Status atualizado com sucesso.', 'status' => $validated['status']]);
        }

        return back()->with('success', 'Status atualizado com sucesso.');
    }
}

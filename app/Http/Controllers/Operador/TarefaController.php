<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use Illuminate\Http\Request;

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

        $validated = $request->validate([
            'status' => 'required|in:em_andamento,concluida,cancelada',
        ]);

        $tarefa->status = $validated['status'];

        if ($validated['status'] === 'concluida') {
            $tarefa->data_conclusao = now();
        }

        $tarefa->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Status atualizado com sucesso.']);
        }

        return back()->with('success', 'Status atualizado com sucesso.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Propriedade;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarefa::with(['propriedade', 'talhao', 'responsavel'])
            ->orderBy('data_prevista');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tarefas = $query->paginate(15)->withQueryString();

        $pendentes = Tarefa::where('status', 'pendente')->count();
        $hoje = Tarefa::whereDate('data_prevista', today())->count();

        return view('admin.tarefas.index', compact('tarefas', 'pendentes', 'hoje'));
    }

    public function create()
    {
        $propriedades = Propriedade::with('talhoes', 'recursos')->orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.tarefas.create', compact('propriedades', 'produtos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $rules = [
            'propriedade_id' => 'required|exists:propriedades,id',
            'talhao_id' => 'nullable|exists:talhoes,id',
            'tipo' => 'required|in:aplicacao,aracao,calagem,irrigacao,manutencao,outro',
            'titulo' => 'required|string|max:255',
            'responsavel_id' => 'required|exists:users,id',
            'recurso_id' => 'nullable|exists:recursos,id',
            'data_prevista' => 'required|date',
            'hora_prevista' => 'nullable|date_format:H:i',
            'observacoes' => 'nullable|string|max:1000',
        ];

        if ($request->input('tipo') === 'aplicacao') {
            $rules['produto_id'] = 'required|exists:produtos,id';
            $rules['dose'] = 'required|numeric|min:0.001';
        }

        $validated = $request->validate($rules);

        $tarefa = Tarefa::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tarefa criada com sucesso.', 'id' => $tarefa->id], 201);
        }

        return redirect()->route('admin.tarefas.index')->with('success', 'Tarefa criada com sucesso.');
    }

    public function show(Tarefa $tarefa)
    {
        $tarefa->load(['propriedade', 'talhao', 'responsavel', 'recurso', 'produto']);

        return view('admin.tarefas.show', compact('tarefa'));
    }
}

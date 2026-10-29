<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Propriedade;
use App\Models\Recomendacao;
use App\Models\Tarefa;
use App\Models\User;
use App\Notifications\AtualizacaoOperacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Recurso;
use App\Services\Clima\InteligenciaClimatica;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarefa::with(['propriedade', 'talhao', 'responsavel', 'recurso'])
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
        $produtos = Produto::with(['propriedades' => fn ($query) => $query->select('propriedades.id', 'propriedades.nome')])
            ->orderBy('nome')->get();
        $usuarios = User::with('propriedades:id')->where('perfil', 'operador')->where('status', 'ativo')->orderBy('name')->get();

        return view('admin.tarefas.create', compact('propriedades', 'produtos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules($request));

        if ($request->input('tipo') !== 'aplicacao') {
            $validated['produto_id'] = null;
            $validated['dose'] = null;
        }

        $tarefa = Tarefa::create($validated);
        $tarefa->responsavel->notify(new AtualizacaoOperacional(
            'Nova tarefa atribuída',
            $tarefa->titulo . ' · ' . $tarefa->propriedade->nome,
            route('operador.tarefas.show', $tarefa),
            'list-check'
        ));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tarefa criada com sucesso.',
                'id' => $tarefa->id,
                'redirect' => route('admin.tarefas.show', $tarefa),
            ], 201);
        }

        return redirect()->route('admin.tarefas.index')->with('success', 'Tarefa criada com sucesso.');
    }

    private function validationRules(Request $request): array
    {
        $rules = [
            'propriedade_id' => 'required|exists:propriedades,id',
            'talhao_id' => ['nullable', Rule::exists('talhoes', 'id')->where('propriedade_id', $request->input('propriedade_id'))],
            'tipo' => 'required|in:aplicacao,aracao,calagem,irrigacao,manutencao,outro',
            'titulo' => 'required|string|max:255',
            'responsavel_id' => [
                'required', Rule::exists('users', 'id')->where('perfil', 'operador')->where('status', 'ativo'),
                function ($attribute, $value, $fail) use ($request) {
                    if (!User::find($value)?->propriedades()->where('propriedades.id', $request->input('propriedade_id'))->exists()) {
                        $fail('O operador precisa ter acesso à propriedade selecionada.');
                    }
                },
            ],
            'recurso_id' => ['nullable', Rule::exists('recursos', 'id')->where('propriedade_id', $request->input('propriedade_id'))],
            'data_prevista' => 'required|date',
            'hora_prevista' => 'nullable|date_format:H:i',
            'observacoes' => 'nullable|string|max:1000',
        ];

        if ($request->input('tipo') === 'aplicacao') {
            $rules['talhao_id'] = ['required', Rule::exists('talhoes', 'id')->where('propriedade_id', $request->input('propriedade_id'))];
            $rules['produto_id'] = [
                'required',
                Rule::exists('produto_propriedade', 'produto_id')
                    ->where('propriedade_id', $request->input('propriedade_id')),
            ];
            $rules['dose'] = 'required|numeric|min:0.001';
        }

        return $rules;
    }

    public function edit(Tarefa $tarefa)
    {
        abort_unless($tarefa->status === 'pendente', 403, 'Só é possível editar tarefas pendentes.');
        $tarefa->load(['propriedade', 'talhao', 'recurso']);
        $propriedades = Propriedade::with('talhoes', 'recursos')->orderBy('nome')->get();
        $produtos = Produto::with(['propriedades' => fn ($query) => $query->select('propriedades.id', 'propriedades.nome')])
            ->orderBy('nome')->get();
        $usuarios = User::where('perfil', 'operador')->where('status', 'ativo')
            ->whereHas('propriedades', fn ($query) => $query->where('propriedades.id', $tarefa->propriedade_id))
            ->orderBy('name')->get();

        return view('admin.tarefas.edit', compact('tarefa', 'propriedades', 'produtos', 'usuarios'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $validated = $request->validate($this->validationRules($request));

        if ($request->input('tipo') !== 'aplicacao') {
            $validated['produto_id'] = null;
            $validated['dose'] = null;
        }

        DB::transaction(function () use ($tarefa, $validated) {
            $tarefa = Tarefa::whereKey($tarefa->id)->lockForUpdate()->firstOrFail();
            abort_unless($tarefa->status === 'pendente', 403, 'Só é possível editar tarefas pendentes.');
            $tarefa->update($validated);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tarefa atualizada com sucesso.',
                'redirect' => route('admin.tarefas.show', $tarefa),
            ]);
        }

        return redirect()->route('admin.tarefas.show', $tarefa)->with('success', 'Tarefa atualizada com sucesso.');
    }

    public function show(Tarefa $tarefa, InteligenciaClimatica $inteligencia)
    {
        $tarefa->load(['propriedade', 'talhao', 'responsavel', 'recurso', 'produto', 'aplicacao', 'recomendacao.agronomo']);
        $orientacaoClimatica = $inteligencia->orientacaoParaTarefa($tarefa);

        return view('admin.tarefas.show', compact('tarefa', 'orientacaoClimatica'));
    }

    public function destroy(Request $request, Tarefa $tarefa)
    {
        abort_unless($tarefa->status === 'pendente', 403, 'Só é possível excluir tarefas pendentes.');
        DB::transaction(function () use ($tarefa) {
            $tarefa = Tarefa::whereKey($tarefa->id)->lockForUpdate()->firstOrFail();
            abort_unless($tarefa->status === 'pendente', 403, 'Só é possível excluir tarefas pendentes.');
            if ($tarefa->recomendacao_id) {
                Recomendacao::whereKey($tarefa->recomendacao_id)->lockForUpdate()->update([
                    'status' => 'pendente',
                    'analisado_por' => null,
                    'analisado_em' => null,
                    'parecer_admin' => 'A tarefa anterior foi removida pelo administrador e a recomendação voltou para análise.',
                ]);
            }
            $tarefa->delete();
        });
        return $request->wantsJson()
            ? response()->json(['message' => 'Tarefa excluída com sucesso.'])
            : redirect()->route('admin.tarefas.index')->with('success', 'Tarefa excluída com sucesso.');
    }

    public function cancelar(Request $request, Tarefa $tarefa)
    {
        $dados = $request->validate(['motivo_cancelamento' => 'required|string|max:1000']);
        DB::transaction(function () use ($tarefa, $dados) {
            $tarefa = Tarefa::whereKey($tarefa->id)->lockForUpdate()->firstOrFail();
            abort_unless(in_array($tarefa->status, ['pendente', 'em_andamento'], true), 422, 'Só é possível cancelar tarefas pendentes ou em andamento.');

            $tarefa->update([
                'status' => 'cancelada',
                'data_conclusao' => null,
                'data_cancelamento' => now(),
                'motivo_cancelamento' => $dados['motivo_cancelamento'],
            ]);

            if ($tarefa->recomendacao_id) {
                Recomendacao::whereKey($tarefa->recomendacao_id)->lockForUpdate()->update([
                    'status' => 'pendente',
                    'analisado_por' => null,
                    'analisado_em' => null,
                    'parecer_admin' => 'A tarefa foi cancelada: '.$dados['motivo_cancelamento'],
                ]);
            }

            if ($tarefa->recurso_id) {
                $recurso = Recurso::whereKey($tarefa->recurso_id)->lockForUpdate()->first();
                $outraTarefaAtiva = Tarefa::where('recurso_id', $tarefa->recurso_id)
                    ->where('id', '!=', $tarefa->id)
                    ->where('status', 'em_andamento')
                    ->exists();
                if ($recurso?->status === 'em_uso' && !$outraTarefaAtiva) {
                    $recurso->update(['status' => 'disponivel']);
                }
            }
        });

        $tarefa->responsavel?->notify(new AtualizacaoOperacional(
            'Tarefa cancelada',
            $tarefa->titulo.' · '.$dados['motivo_cancelamento'],
            route('operador.tarefas.show', $tarefa),
            'x'
        ));

        return back()->with('success', 'Tarefa cancelada; o motivo foi registrado e o recurso liberado quando aplicável.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recomendacao;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RecomendacaoController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['pendente', 'tarefa_criada', 'recusada'])],
        ]);
        $query = Recomendacao::with(['propriedade', 'talhao', 'agronomo', 'tarefa'])->latest();
        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }
        $recomendacoes = $query->paginate(15)->withQueryString();

        return view('admin.recomendacoes.index', compact('recomendacoes'));
    }

    public function show(Recomendacao $recomendacao)
    {
        $recomendacao->load(['propriedade', 'talhao.cultura', 'agronomo', 'produto', 'analisadoPor', 'tarefa.responsavel']);
        $dadosAplicacaoValidos = $recomendacao->tipo !== 'aplicacao'
            || ($recomendacao->talhao_id && $recomendacao->produto_id && $recomendacao->dose !== null);
        $operadores = User::where('perfil', 'operador')->where('status', 'ativo')
            ->whereHas('propriedades', fn ($query) => $query->where('propriedades.id', $recomendacao->propriedade_id))
            ->orderBy('name')->get(['id', 'name']);
        $recursos = $recomendacao->propriedade->recursos()->where('status', 'disponivel')->orderBy('nome')->get();

        return view('admin.recomendacoes.show', compact('recomendacao', 'operadores', 'recursos', 'dadosAplicacaoValidos'));
    }

    public function criarTarefa(Request $request, Recomendacao $recomendacao)
    {
        abort_unless($recomendacao->status === 'pendente', 403, 'Esta recomendação já foi analisada.');
        if ($recomendacao->tipo === 'aplicacao' && (!$recomendacao->talhao_id || !$recomendacao->produto_id || $recomendacao->dose === null)) {
            throw ValidationException::withMessages([
                'responsavel_id' => 'A recomendação de aplicação perdeu o talhão, produto ou dose necessários. Solicite uma nova recomendação ao agrônomo.',
            ]);
        }
        $propriedadeId = $recomendacao->propriedade_id;
        $validated = $request->validate([
            'responsavel_id' => [
                'required',
                Rule::exists('users', 'id')->where('perfil', 'operador')->where('status', 'ativo'),
                function ($attribute, $value, $fail) use ($propriedadeId) {
                    if (!User::find($value)?->propriedades()->where('propriedades.id', $propriedadeId)->exists()) {
                        $fail('O operador selecionado não tem acesso a esta propriedade.');
                    }
                },
            ],
            'recurso_id' => ['nullable', Rule::exists('recursos', 'id')->where('propriedade_id', $propriedadeId)->where('status', 'disponivel')],
            'data_prevista' => ['required', 'date'],
            'hora_prevista' => ['nullable', 'date_format:H:i'],
            'parecer_admin' => ['nullable', 'string', 'max:3000'],
        ]);

        $tarefa = DB::transaction(function () use ($recomendacao, $validated) {
            $recomendacao = Recomendacao::whereKey($recomendacao->id)->lockForUpdate()->firstOrFail();
            abort_unless($recomendacao->status === 'pendente', 409, 'Esta recomendação já foi analisada.');

            if ($recomendacao->tipo === 'aplicacao') {
                $estoque = DB::table('produto_propriedade')
                    ->where('produto_id', $recomendacao->produto_id)
                    ->where('propriedade_id', $recomendacao->propriedade_id)
                    ->lockForUpdate()->first();
                if (!$estoque || (float) $estoque->estoque_atual < (float) $recomendacao->dose) {
                    throw ValidationException::withMessages([
                        'responsavel_id' => 'Estoque insuficiente para transformar esta recomendação em tarefa. Reabasteça o produto ou revise a recomendação.',
                    ]);
                }
            }

            $tarefa = Tarefa::create([
                'recomendacao_id' => $recomendacao->id,
                'propriedade_id' => $recomendacao->propriedade_id,
                'talhao_id' => $recomendacao->talhao_id,
                'tipo' => $recomendacao->tipo,
                'titulo' => $recomendacao->titulo,
                'responsavel_id' => $validated['responsavel_id'],
                'recurso_id' => $validated['recurso_id'] ?? null,
                'produto_id' => $recomendacao->tipo === 'aplicacao' ? $recomendacao->produto_id : null,
                'dose' => $recomendacao->tipo === 'aplicacao' ? $recomendacao->dose : null,
                'status' => 'pendente',
                'data_prevista' => $validated['data_prevista'],
                'hora_prevista' => $validated['hora_prevista'] ?? null,
                'observacoes' => "Diagnóstico: {$recomendacao->diagnostico}\nOrientação técnica: {$recomendacao->orientacao}",
            ]);

            $recomendacao->update([
                'status' => 'tarefa_criada',
                'analisado_por' => auth()->id(),
                'analisado_em' => now(),
                'parecer_admin' => $validated['parecer_admin'] ?? null,
            ]);

            return $tarefa;
        });

        return redirect()->route('admin.tarefas.show', $tarefa)
            ->with('success', 'Recomendação aprovada e convertida em tarefa.');
    }

    public function recusar(Request $request, Recomendacao $recomendacao)
    {
        abort_unless($recomendacao->status === 'pendente', 403, 'Esta recomendação já foi analisada.');
        $validated = $request->validate([
            'parecer_admin' => ['required', 'string', 'min:5', 'max:3000'],
        ]);

        DB::transaction(function () use ($recomendacao, $validated) {
            $recomendacao = Recomendacao::whereKey($recomendacao->id)->lockForUpdate()->firstOrFail();
            abort_unless($recomendacao->status === 'pendente', 409, 'Esta recomendação já foi analisada.');
            $recomendacao->update([
                'status' => 'recusada',
                'analisado_por' => auth()->id(),
                'analisado_em' => now(),
                'parecer_admin' => $validated['parecer_admin'],
            ]);
        });

        return redirect()->route('admin.recomendacoes.show', $recomendacao)
            ->with('success', 'Recomendação recusada. O parecer ficará visível para o agrônomo.');
    }
}

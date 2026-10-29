<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Recurso;
use App\Models\Tarefa;
use App\Models\Aplicacao;
use App\Models\Produto;
use App\Models\MovimentacaoEstoque;
use App\Models\Recomendacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Clima\InteligenciaClimatica;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarefa::with(['propriedade', 'talhao'])
            ->where('responsavel_id', auth()->id())
            ->whereHas('propriedade.usuarios', fn ($q) => $q->where('users.id', auth()->id()))
            ->orderBy('data_prevista');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tarefas = $query->paginate(15)->withQueryString();

        return view('operador.tarefas.index', compact('tarefas'));
    }

    public function show(Tarefa $tarefa, InteligenciaClimatica $inteligencia)
    {
        abort_unless($tarefa->responsavel_id === auth()->id() && $tarefa->propriedade->usuarios()->where('users.id', auth()->id())->exists(), 403);

        $tarefa->load(['propriedade', 'talhao', 'recurso', 'produto', 'aplicacao']);
        $orientacaoClimatica = $inteligencia->orientacaoParaTarefa($tarefa);

        return view('operador.tarefas.show', compact('tarefa', 'orientacaoClimatica'));
    }

    public function atualizarStatus(Request $request, Tarefa $tarefa)
    {
        abort_unless($tarefa->responsavel_id === auth()->id(), 403);

        abort_unless($tarefa->propriedade->usuarios()->where('users.id', auth()->id())->exists(), 403);
        $rules = ['status' => 'required|in:em_andamento,concluida,cancelada'];
        if ($request->input('status') === 'cancelada') {
            $rules['motivo_cancelamento'] = 'required|string|max:1000';
        }
        if ($tarefa->tipo === 'aplicacao' && $request->input('status') === 'concluida') {
            $rules += [
                'dose_realizada' => 'required|numeric|decimal:0,3|min:0.001|max:9999999.999',
                'equipamento' => 'nullable|string|max:255',
                'temperatura' => 'nullable|numeric|between:-50,70',
                'umidade' => 'nullable|numeric|between:0,100',
                'velocidade_vento' => 'nullable|numeric|min:0|max:500',
                'precipitacao' => 'nullable|numeric|min:0|max:999999',
                'observacoes_aplicacao' => 'nullable|string|max:2000',
            ];
        }
        $validated = $request->validate($rules);

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
            $tarefa->data_cancelamento = $novoStatus === 'cancelada' ? now() : null;
            $tarefa->motivo_cancelamento = $novoStatus === 'cancelada' ? $validated['motivo_cancelamento'] : null;
            $tarefa->save();

            if ($novoStatus === 'cancelada' && $tarefa->recomendacao_id) {
                Recomendacao::whereKey($tarefa->recomendacao_id)->lockForUpdate()->update([
                    'status' => 'pendente',
                    'analisado_por' => null,
                    'analisado_em' => null,
                    'parecer_admin' => 'A tarefa foi cancelada pelo operador: '.$validated['motivo_cancelamento'],
                ]);
            }

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
                        if ($recurso->status === 'em_uso' && !$outraTarefaEmAndamento) {
                            $recurso->status = 'disponivel';
                            $recurso->save();
                        }
                    }
                }
            }

            if ($novoStatus === 'concluida' && $tarefa->tipo === 'aplicacao') {
                abort_unless($tarefa->talhao_id && $tarefa->produto_id, 422, 'A tarefa precisa ter talhão e produto para registrar a aplicação.');
                abort_unless(! $tarefa->aplicacao()->exists(), 422, 'Esta tarefa já possui uma aplicação registrada.');

                $estoque = DB::table('produto_propriedade')
                    ->where('produto_id', $tarefa->produto_id)
                    ->where('propriedade_id', $tarefa->propriedade_id)
                    ->lockForUpdate()
                    ->first();
                abort_unless($estoque, 422, 'O produto não possui estoque cadastrado para esta propriedade.');
                $dose = (float) $validated['dose_realizada'];
                $linhasAtualizadas = DB::table('produto_propriedade')
                    ->where('id', $estoque->id)
                    ->where('estoque_atual', '>=', $dose)
                    ->decrement('estoque_atual', $dose, ['updated_at' => now()]);
                abort_unless($linhasAtualizadas === 1, 422, 'Estoque insuficiente para registrar a quantidade aplicada.');
                $saldoNovo = round((float) $estoque->estoque_atual - $dose, 3);

                Aplicacao::create([
                    'tarefa_id' => $tarefa->id,
                    'talhao_id' => $tarefa->talhao_id,
                    'produto_id' => $tarefa->produto_id,
                    'usuario_id' => auth()->id(),
                    'status' => 'realizada',
                    'dose' => $dose,
                    'equipamento' => $validated['equipamento'] ?? $tarefa->recurso?->nome,
                    'data_aplicacao' => today(),
                    'hora_aplicacao' => now()->format('H:i:s'),
                    'temperatura' => $validated['temperatura'] ?? null,
                    'umidade' => $validated['umidade'] ?? null,
                    'velocidade_vento' => $validated['velocidade_vento'] ?? null,
                    'precipitacao' => $validated['precipitacao'] ?? null,
                    'observacoes' => $validated['observacoes_aplicacao'] ?? null,
                ]);
                MovimentacaoEstoque::create([
                    'produto_propriedade_id' => $estoque->id,
                    'tarefa_id' => $tarefa->id,
                    'usuario_id' => auth()->id(),
                    'tipo' => 'saida',
                    'quantidade' => $dose,
                    'saldo_anterior' => $estoque->estoque_atual,
                    'saldo_atual' => $saldoNovo,
                    'motivo' => 'Aplicação registrada · tarefa #'.$tarefa->id,
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => $tarefa->tipo === 'aplicacao' && $validated['status'] === 'concluida' ? 'Aplicação registrada e estoque atualizado.' : 'Status atualizado com sucesso.', 'status' => $validated['status']]);
        }

        return back()->with('success', 'Status atualizado com sucesso.');
    }
}

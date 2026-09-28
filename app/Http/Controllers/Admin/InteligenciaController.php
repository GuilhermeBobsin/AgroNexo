<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegraClimatica;
use App\Models\Tarefa;
use App\Services\Clima\InteligenciaClimatica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;

class InteligenciaController extends Controller
{
    public function index(InteligenciaClimatica $inteligencia)
    {
        $regras = RegraClimatica::orderBy('tipo_tarefa')->orderBy('nome')->get();
        $tarefas = Tarefa::with(['propriedade', 'talhao'])
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->whereDate('data_prevista', '>=', today())
            ->orderBy('data_prevista')->limit(100)->get()
            ->map(function (Tarefa $tarefa) use ($inteligencia) {
                $tarefa->alertas_climaticos = $inteligencia->alertasParaTarefa($tarefa);
                $tarefa->clima_disponivel = $inteligencia->temPrevisaoParaTarefa($tarefa);
                return $tarefa;
            });
        $atualizadoEm = \App\Models\PrevisaoClimatica::max('atualizado_em');

        return view('admin.inteligencia.index', compact('regras', 'tarefas', 'atualizadoEm'));
    }

    public function sincronizar()
    {
        $codigo = Artisan::call('clima:atualizar-previsoes');
        $saida = trim(Artisan::output());
        return back()->with($codigo === 0 ? 'success' : 'error', ($codigo === 0
            ? 'Atualização de previsões concluída.'
            : 'A atualização terminou com falhas.') . ($saida !== '' ? ' ' . $saida : ''));
    }

    public function storeRegra(Request $request)
    {
        RegraClimatica::create($this->validarRegra($request));
        return back()->with('success', 'Regra climática criada.');
    }

    public function updateRegra(Request $request, RegraClimatica $regra)
    {
        $regra->update($this->validarRegra($request));
        return back()->with('success', 'Regra climática atualizada.');
    }

    public function destroyRegra(RegraClimatica $regra)
    {
        $regra->delete();
        return back()->with('success', 'Regra climática removida.');
    }

    private function validarRegra(Request $request): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'tipo_tarefa' => ['nullable', Rule::in(['aplicacao', 'aracao', 'calagem', 'irrigacao', 'manutencao', 'outro'])],
            'variavel' => ['required', Rule::in(['temperatura', 'umidade', 'velocidade_vento', 'rajada_vento', 'precipitacao', 'chance_chuva'])],
            'agregacao' => ['required', Rule::in(['maximo', 'soma', 'media'])],
            'janela_horas' => ['required', 'integer', 'between:1,168'],
            'operador' => ['required', Rule::in(['maior', 'maior_igual', 'menor', 'menor_igual', 'igual'])],
            'valor' => ['required', 'numeric', 'between:-10000,10000'],
            'unidade' => ['nullable', 'string', 'max:20'],
            'gravidade' => ['required', Rule::in(['baixa', 'media', 'alta'])],
            'mensagem' => ['required', 'string', 'max:1000'],
            'ativa' => ['nullable', 'boolean'],
        ]) + ['ativa' => $request->boolean('ativa')];
    }
}

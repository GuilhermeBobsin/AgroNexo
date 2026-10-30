<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use App\Models\Talhao;
use App\Services\Clima\InteligenciaClimatica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;

class InteligenciaController extends Controller
{
    public function index(InteligenciaClimatica $inteligencia)
    {
        $propriedades = Propriedade::with('talhoes')->whereHas('talhoes')->orderBy('nome')->get();
        $atualizacoes = PrevisaoClimatica::whereIn('talhao_id', $propriedades->flatMap(fn (Propriedade $p) => $p->talhoes)->pluck('id'))
            ->selectRaw('talhao_id, MAX(atualizado_em) as ultima_atualizacao')
            ->groupBy('talhao_id')
            ->pluck('ultima_atualizacao', 'talhao_id');
        $locais = $propriedades->flatMap(fn (Propriedade $propriedade) => $propriedade->talhoes->map(fn (Talhao $talhao) => [
            'propriedade' => $propriedade,
            'talhao' => $talhao,
            'dias' => $inteligencia->semana($talhao),
            'atualizado_em' => $atualizacoes->get($talhao->id),
        ]));
        $atualizadoEm = PrevisaoClimatica::max('atualizado_em');

        return view('admin.inteligencia.index', compact('locais', 'atualizadoEm'));
    }

    public function avaliar(Request $request, InteligenciaClimatica $inteligencia)
    {
        $dados = $request->validate([
            'tipo' => ['required', 'in:aplicacao,aracao,calagem'],
            'talhao_id' => ['required', 'integer', 'exists:talhoes,id'],
            'data_prevista' => ['required', 'date'],
            'hora_prevista' => ['nullable', 'date_format:H:i'],
        ]);
        $talhao = Talhao::findOrFail($dados['talhao_id']);

        return response()->json($inteligencia->avaliarTipoDia(
            $dados['tipo'],
            $talhao,
            $dados['data_prevista'],
            $dados['hora_prevista'] ?? null,
        ));
    }

    public function sincronizar()
    {
        $solicitadaEm = now();
        $codigo = Artisan::call('clima:atualizar-previsoes');
        $saida = trim(Artisan::output());
        $resposta = back()->with($codigo === 0 ? 'success' : 'error', ($codigo === 0
            ? 'Sincronização climática enviada para a fila. Os dados desta tela mudam após o worker processar os jobs. No ambiente local, mantenha `composer run dev` ou `php artisan queue:listen` em execução.'
            : 'Não foi possível solicitar a sincronização climática.') . ($saida !== '' ? ' ' . $saida : ''));

        if ($codigo === 0) {
            $resposta->with('clima_sync_solicitada_em', $solicitadaEm->toIso8601String());
        }

        return $resposta;
    }

    public function status(Request $request)
    {
        $dados = $request->validate(['desde' => ['required', 'date']]);
        $desde = Carbon::parse($dados['desde']);
        $ultimaAtualizacao = PrevisaoClimatica::where('atualizado_em', '>=', $desde)->max('atualizado_em');

        return response()->json([
            'atualizada' => $ultimaAtualizacao !== null,
            'ultima_atualizacao' => $ultimaAtualizacao,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use App\Models\Talhao;
use App\Services\Clima\InteligenciaClimatica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class InteligenciaController extends Controller
{
    public function index(InteligenciaClimatica $inteligencia)
    {
        $propriedades = Propriedade::with('talhoes')->whereHas('talhoes')->orderBy('nome')->get();
        $locais = $propriedades->flatMap(fn (Propriedade $propriedade) => $propriedade->talhoes->map(fn (Talhao $talhao) => [
            'propriedade' => $propriedade,
            'talhao' => $talhao,
            'dias' => $inteligencia->semana($talhao),
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
        $codigo = Artisan::call('clima:atualizar-previsoes');
        $saida = trim(Artisan::output());
        return back()->with($codigo === 0 ? 'success' : 'error', ($codigo === 0
            ? 'Previsões atualizadas.'
            : 'A atualização terminou com falhas.') . ($saida !== '' ? ' ' . $saida : ''));
    }
}

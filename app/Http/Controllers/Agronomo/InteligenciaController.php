<?php

namespace App\Http\Controllers\Agronomo;

use App\Http\Controllers\Controller;
use App\Models\PrevisaoClimatica;
use App\Models\RegraClimatica;
use App\Models\Tarefa;
use App\Services\Clima\InteligenciaClimatica;

class InteligenciaController extends Controller
{
    public function index(InteligenciaClimatica $inteligencia)
    {
        $ids = auth()->user()->propriedades()->pluck('propriedades.id');
        $regras = RegraClimatica::where('ativa', true)->orderBy('tipo_tarefa')->get();
        $tarefas = Tarefa::with(['propriedade', 'talhao'])
            ->whereIn('propriedade_id', $ids)->whereIn('status', ['pendente', 'em_andamento'])
            ->whereDate('data_prevista', '>=', today())->orderBy('data_prevista')->limit(100)->get()
            ->map(function (Tarefa $tarefa) use ($inteligencia) {
                $tarefa->alertas_climaticos = $inteligencia->alertasParaTarefa($tarefa);
                $tarefa->clima_disponivel = $inteligencia->temPrevisaoParaTarefa($tarefa);
                return $tarefa;
            });
        $atualizadoEm = PrevisaoClimatica::whereHas('talhao', fn ($q) => $q->whereIn('propriedade_id', $ids))->max('atualizado_em');

        return view('agronomo.inteligencia.index', compact('regras', 'tarefas', 'atualizadoEm'));
    }
}

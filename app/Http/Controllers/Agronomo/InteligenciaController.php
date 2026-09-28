<?php

namespace App\Http\Controllers\Agronomo;

use App\Http\Controllers\Controller;
use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use App\Models\Talhao;
use App\Services\Clima\InteligenciaClimatica;

class InteligenciaController extends Controller
{
    public function index(InteligenciaClimatica $inteligencia)
    {
        $propriedades = auth()->user()->propriedades()->with('talhoes')->whereHas('talhoes')->orderBy('nome')->get();
        $ids = $propriedades->modelKeys();
        $locais = $propriedades->flatMap(fn (Propriedade $propriedade) => $propriedade->talhoes->map(fn (Talhao $talhao) => [
            'propriedade' => $propriedade,
            'talhao' => $talhao,
            'dias' => $inteligencia->semana($talhao),
        ]));
        $atualizadoEm = PrevisaoClimatica::whereHas('talhao', fn ($q) => $q->whereIn('propriedade_id', $ids))->max('atualizado_em');

        return view('agronomo.inteligencia.index', compact('locais', 'atualizadoEm'));
    }
}

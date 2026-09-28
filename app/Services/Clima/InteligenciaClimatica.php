<?php

namespace App\Services\Clima;

use App\Models\RegraClimatica;
use App\Models\Tarefa;
use Illuminate\Support\Carbon;

class InteligenciaClimatica
{
    public function temPrevisaoParaTarefa(Tarefa $tarefa): bool
    {
        if (!$tarefa->talhao_id) {
            return false;
        }

        $horas = RegraClimatica::where('ativa', true)
            ->where(fn ($q) => $q->whereNull('tipo_tarefa')->orWhere('tipo_tarefa', $tarefa->tipo))
            ->max('janela_horas');
        $horas = $horas ?: 168;

        $inicio = Carbon::parse($tarefa->data_prevista->format('Y-m-d').' '.($tarefa->hora_prevista ?: '00:00:00'), config('app.timezone'));
        return $tarefa->talhao->previsoesClimaticas()
            ->whereBetween('previsto_para', [$inicio, $inicio->copy()->addHours($horas)])
            ->where('previsto_para', '>=', now())
            ->exists();
    }

    public function alertasParaTarefa(Tarefa $tarefa): array
    {
        if (!$tarefa->talhao_id) {
            return [];
        }

        $regras = RegraClimatica::where('ativa', true)
            ->where(fn ($q) => $q->whereNull('tipo_tarefa')->orWhere('tipo_tarefa', $tarefa->tipo))
            ->get();
        if ($regras->isEmpty()) {
            return [];
        }

        $alertas = [];
        foreach ($regras as $regra) {
            $inicio = Carbon::parse($tarefa->data_prevista->format('Y-m-d').' '.($tarefa->hora_prevista ?: '00:00:00'), config('app.timezone'));
            $fim = $inicio->copy()->addHours($regra->janela_horas);
            $leituras = $tarefa->talhao->previsoesClimaticas()
                ->whereBetween('previsto_para', [$inicio, $fim])
                ->where('previsto_para', '>=', now())
                ->get([$regra->variavel]);
            if ($leituras->isEmpty()) {
                continue;
            }

            $valores = $leituras->pluck($regra->variavel)->filter(fn ($valor) => $valor !== null)->map(fn ($valor) => (float) $valor);
            if ($valores->isEmpty()) {
                continue;
            }
            $medido = match ($regra->agregacao) {
                'soma' => $valores->sum(),
                'media' => $valores->avg(),
                default => $valores->max(),
            };
            $dispara = match ($regra->operador) {
                'maior' => $medido > $regra->valor,
                'maior_igual' => $medido >= $regra->valor,
                'menor' => $medido < $regra->valor,
                'menor_igual' => $medido <= $regra->valor,
                'igual' => abs($medido - (float) $regra->valor) < 0.00001,
            };
            if ($dispara) {
                $alertas[] = [
                    'nome' => $regra->nome,
                    'mensagem' => $regra->mensagem,
                    'gravidade' => $regra->gravidade,
                    'valor' => $medido,
                    'unidade' => $regra->unidade,
                    'inicio' => $inicio,
                    'fim' => $fim,
                ];
            }
        }

        return $alertas;
    }
}

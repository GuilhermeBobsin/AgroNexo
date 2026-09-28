<?php

namespace App\Services\Clima;

use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenMeteoService
{
    public function atualizarPropriedade(Propriedade $propriedade): int
    {
        if ($propriedade->latitude === null || $propriedade->longitude === null) {
            throw new RuntimeException("A propriedade {$propriedade->nome} não possui coordenadas cadastradas.");
        }

        $dados = Http::baseUrl('https://api.open-meteo.com/v1')
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 500)
            ->get('/forecast', [
                'latitude' => $propriedade->latitude,
                'longitude' => $propriedade->longitude,
                'hourly' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,wind_gusts_10m,precipitation,precipitation_probability',
                'forecast_days' => 7,
                'timezone' => 'America/Sao_Paulo',
                'wind_speed_unit' => 'kmh',
            ])->throw()->json();

        $horas = $dados['hourly'] ?? [];
        $timestamps = $horas['time'] ?? [];
        if (!$timestamps) {
            throw new RuntimeException("A API não retornou previsão horária para {$propriedade->nome}.");
        }

        $now = now();
        $atualizadas = 0;
        foreach ($propriedade->talhoes()->pluck('id') as $talhaoId) {
            foreach ($timestamps as $i => $timestamp) {
                $previstoPara = \Illuminate\Support\Carbon::parse($timestamp, 'America/Sao_Paulo');
                if ($previstoPara->lt($now->copy()->startOfHour())) {
                    continue;
                }
                PrevisaoClimatica::updateOrCreate(
                    ['talhao_id' => $talhaoId, 'previsto_para' => $previstoPara],
                    [
                        'temperatura' => $horas['temperature_2m'][$i] ?? null,
                        'umidade' => $horas['relative_humidity_2m'][$i] ?? null,
                        'velocidade_vento' => $horas['wind_speed_10m'][$i] ?? null,
                        'rajada_vento' => $horas['wind_gusts_10m'][$i] ?? null,
                        'precipitacao' => $horas['precipitation'][$i] ?? null,
                        'chance_chuva' => $horas['precipitation_probability'][$i] ?? null,
                        'fonte' => 'open-meteo',
                        'atualizado_em' => $now,
                    ]
                );
                $atualizadas++;
            }
        }

        return $atualizadas;
    }
}

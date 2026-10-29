<?php

namespace App\Services\Clima;

use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenMeteoService
{
    public function atualizarPropriedade(Propriedade $propriedade): int
    {
        if ($propriedade->latitude === null || $propriedade->longitude === null) {
            throw new RuntimeException("A propriedade {$propriedade->nome} não possui coordenadas cadastradas.");
        }

        // O endpoint geral usa o melhor modelo disponível e oferece probabilidade de chuva e ET₀.
        $parametros = [
            'latitude' => $propriedade->latitude,
            'longitude' => $propriedade->longitude,
            'hourly' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,wind_gusts_10m,precipitation,precipitation_probability,sunshine_duration,soil_moisture_3_to_9cm,et0_fao_evapotranspiration,weather_code',
            'past_days' => config('clima.dias_historico', 7),
            'forecast_days' => config('clima.dias_previsao', 7),
            'timezone' => 'America/Sao_Paulo',
            'wind_speed_unit' => 'kmh',
        ];
        if ($apiKey = config('clima.open_meteo.api_key')) {
            $parametros['apikey'] = $apiKey;
        }

        $dados = Http::baseUrl(config('clima.open_meteo.base_url'))
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 500)
            ->get('/forecast', $parametros)->throw()->json();

        $horas = $dados['hourly'] ?? [];
        $timestamps = $horas['time'] ?? [];
        $variaveisObrigatorias = [
            'temperature_2m', 'relative_humidity_2m', 'wind_speed_10m', 'wind_gusts_10m',
            'precipitation', 'precipitation_probability', 'sunshine_duration',
            'soil_moisture_3_to_9cm', 'et0_fao_evapotranspiration', 'weather_code',
        ];
        if (!$timestamps || collect($variaveisObrigatorias)->contains(
            fn (string $variavel) => !isset($horas[$variavel]) || !is_array($horas[$variavel]) || count($horas[$variavel]) !== count($timestamps)
        )) {
            throw new RuntimeException("A API não retornou previsão horária para {$propriedade->nome}.");
        }

        $now = now();
        $atualizadas = 0;
        $linhasPorTalhao = [];
        $idsTalhoes = $propriedade->talhoes()->pluck('id');
        foreach ($idsTalhoes as $talhaoId) {
            foreach ($timestamps as $i => $timestamp) {
                $previstoPara = \Illuminate\Support\Carbon::parse($timestamp, 'America/Sao_Paulo');
                $linhasPorTalhao[$talhaoId][] = [
                    'talhao_id' => $talhaoId,
                    'previsto_para' => $previstoPara,
                    'tipo_dado' => $previstoPara->lt($now->copy()->startOfHour()) ? 'historico_estimado' : 'previsao',
                    'temperatura' => $horas['temperature_2m'][$i],
                    'umidade' => $horas['relative_humidity_2m'][$i],
                    'velocidade_vento' => $horas['wind_speed_10m'][$i],
                    'rajada_vento' => $horas['wind_gusts_10m'][$i],
                    'precipitacao' => $horas['precipitation'][$i],
                    'chance_chuva' => $horas['precipitation_probability'][$i],
                    'duracao_sol' => $horas['sunshine_duration'][$i],
                    'umidade_solo' => $horas['soil_moisture_3_to_9cm'][$i],
                    'evapotranspiracao' => $horas['et0_fao_evapotranspiration'][$i],
                    'codigo_tempo' => $horas['weather_code'][$i],
                    'fonte' => 'open-meteo',
                    'atualizado_em' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $atualizadas += count($timestamps);
        }

        // Só substitui previsões depois de receber e validar a resposta completa da API.
        DB::transaction(function () use ($linhasPorTalhao, $now) {
            foreach ($linhasPorTalhao as $talhaoId => $linhas) {
                PrevisaoClimatica::where('talhao_id', $talhaoId)
                    ->where('tipo_dado', 'previsao')
                    ->where('previsto_para', '>=', $now->copy()->startOfHour())
                    ->delete();
                foreach (array_chunk($linhas, 100) as $lote) {
                    PrevisaoClimatica::upsert(
                        $lote,
                        ['talhao_id', 'previsto_para'],
                        ['tipo_dado', 'temperatura', 'umidade', 'velocidade_vento', 'rajada_vento', 'precipitacao',
                            'chance_chuva', 'duracao_sol', 'umidade_solo', 'evapotranspiracao', 'codigo_tempo',
                            'fonte', 'atualizado_em', 'updated_at']
                    );
                }
            }
        });

        return $atualizadas;
    }
}

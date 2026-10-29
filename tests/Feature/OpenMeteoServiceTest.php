<?php

namespace Tests\Feature;

use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use App\Models\User;
use App\Services\Clima\OpenMeteoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class OpenMeteoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fetches_and_persists_the_complete_hourly_agricultural_dataset(): void
    {
        $propriedade = $this->propriedadeComTalhao();
        Http::fake(['api.open-meteo.com/*' => Http::response($this->payload(), 200)]);

        $gravadas = app(OpenMeteoService::class)->atualizarPropriedade($propriedade);

        $this->assertSame(24, $gravadas);
        $this->assertDatabaseCount('previsoes_climaticas', 24);
        $this->assertDatabaseHas('previsoes_climaticas', [
            'talhao_id' => $propriedade->talhoes()->value('id'),
            'evapotranspiracao' => 0.125,
            'umidade_solo' => 0.2345,
            'chance_chuva' => 25,
            'fonte' => 'open-meteo',
        ]);

        Http::assertSent(fn ($request) => parse_url($request->url(), PHP_URL_PATH) === '/v1/forecast'
            && str_contains($request['hourly'], 'et0_fao_evapotranspiration')
            && $request['timezone'] === 'America/Sao_Paulo'
            && $request['wind_speed_unit'] === 'kmh');
    }

    public function test_it_does_not_replace_cached_rows_when_the_api_payload_is_incomplete(): void
    {
        $propriedade = $this->propriedadeComTalhao();
        $talhaoId = $propriedade->talhoes()->value('id');
        PrevisaoClimatica::create([
            'talhao_id' => $talhaoId,
            'previsto_para' => now()->addHour()->startOfHour(),
            'tipo_dado' => 'previsao',
            'velocidade_vento' => 7,
            'atualizado_em' => now()->subHour(),
        ]);
        $payload = $this->payload();
        unset($payload['hourly']['et0_fao_evapotranspiration']);
        Http::fake(['api.open-meteo.com/*' => Http::response($payload, 200)]);

        try {
            app(OpenMeteoService::class)->atualizarPropriedade($propriedade);
            $this->fail('A resposta incompleta deveria ser rejeitada.');
        } catch (RuntimeException) {
            $this->assertDatabaseCount('previsoes_climaticas', 1);
            $this->assertDatabaseHas('previsoes_climaticas', ['talhao_id' => $talhaoId, 'velocidade_vento' => 7]);
        }
    }

    public function test_admin_intelligence_screen_renders_location_freshness_and_weather_metrics(): void
    {
        $propriedade = $this->propriedadeComTalhao();
        $talhaoId = $propriedade->talhoes()->value('id');
        PrevisaoClimatica::create([
            'talhao_id' => $talhaoId,
            'previsto_para' => today()->setHour(10),
            'tipo_dado' => 'previsao',
            'velocidade_vento' => 5,
            'rajada_vento' => 8,
            'precipitacao' => 0,
            'chance_chuva' => 10,
            'duracao_sol' => 3600,
            'umidade_solo' => 0.2,
            'evapotranspiracao' => 0.125,
            'atualizado_em' => now(),
        ]);
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);

        $this->actingAs($admin)->get(route('admin.inteligencia.index'))
            ->assertOk()
            ->assertSee('Fazenda teste · Talhão 1')
            ->assertSee('Dados recentes')
            ->assertSee('Vento máx.')
            ->assertSee('ET₀');
    }

    private function propriedadeComTalhao(): Propriedade
    {
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda teste', 'localizacao' => 'RS', 'latitude' => -29.0, 'longitude' => -53.0,
        ]);
        $propriedade->talhoes()->create(['nome' => 'Talhão 1', 'area' => 10]);

        return $propriedade;
    }

    private function payload(): array
    {
        $horas = array_fill(0, 24, 1);
        $payload = ['hourly' => ['time' => []]];
        foreach (range(0, 23) as $hora) {
            $payload['hourly']['time'][] = now()->startOfDay()->addHours($hora)->format('Y-m-d\TH:i');
        }
        foreach ([
            'temperature_2m', 'relative_humidity_2m', 'wind_speed_10m', 'wind_gusts_10m', 'precipitation',
            'precipitation_probability', 'sunshine_duration', 'soil_moisture_3_to_9cm',
            'et0_fao_evapotranspiration', 'weather_code',
        ] as $variable) {
            $payload['hourly'][$variable] = $horas;
        }
        $payload['hourly']['soil_moisture_3_to_9cm'] = array_fill(0, 24, 0.2345);
        $payload['hourly']['precipitation_probability'] = array_fill(0, 24, 25);
        $payload['hourly']['et0_fao_evapotranspiration'] = array_fill(0, 24, 0.125);

        return $payload;
    }
}

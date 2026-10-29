<?php

namespace Tests\Feature;

use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use App\Models\Talhao;
use App\Services\Clima\InteligenciaClimatica;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class InteligenciaClimaticaRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_rain_blocks_spraying_even_when_wind_is_inside_the_allowed_band(): void
    {
        $this->travelTo(today()->setTime(5, 0));
        $talhao = $this->talhao();
        $this->preverDia(today(), 5, 0.0);
        PrevisaoClimatica::where('previsto_para', today()->setTime(10, 0))->update(['precipitacao' => 0.2]);

        $resultado = app(InteligenciaClimatica::class)->avaliarTipoDia('pulverizacao', $talhao, today()->toDateString());

        $this->assertSame('nao_indicado', $resultado['status']);
        $this->assertStringContainsString('precipitação prevista', $resultado['mensagem']);
    }

    public function test_spraying_is_not_indicated_below_the_minimum_wind_speed(): void
    {
        $this->travelTo(today()->setTime(5, 0));
        $talhao = $this->talhao();
        $this->preverDia(today(), 2.5, 0.0);

        $resultado = app(InteligenciaClimatica::class)->avaliarTipoDia('pulverizacao', $talhao, today()->toDateString());

        $this->assertSame('nao_indicado', $resultado['status']);
        $this->assertStringContainsString('inversão térmica', $resultado['mensagem']);
    }

    public function test_spraying_is_not_indicated_above_the_maximum_wind_speed(): void
    {
        $this->travelTo(today()->setTime(5, 0));
        $talhao = $this->talhao();
        $this->preverDia(today(), 11, 0.0);

        $resultado = app(InteligenciaClimatica::class)->avaliarTipoDia('pulverizacao', $talhao, today()->toDateString());

        $this->assertSame('nao_indicado', $resultado['status']);
        $this->assertStringContainsString('deriva', $resultado['mensagem']);
    }

    public function test_plowing_is_not_indicated_the_day_after_relevant_rain(): void
    {
        $this->travelTo(today()->setTime(5, 0));
        $talhao = $this->talhao();
        $this->preverDiaCompleto(today(), 5, 0);
        PrevisaoClimatica::where('previsto_para', today()->setTime(14, 0))->update(['precipitacao' => 2.0, 'duracao_sol' => 0]);

        $resultado = app(InteligenciaClimatica::class)->avaliarTipoDia('aracao', $talhao, today()->addDay()->toDateString());

        $this->assertSame('nao_indicado', $resultado['status']);
        $this->assertStringContainsString('chuva relevante ontem', $resultado['mensagem']);
    }

    public function test_plowing_is_unavailable_when_the_previous_day_history_is_missing(): void
    {
        $talhao = $this->talhao();

        $resultado = app(InteligenciaClimatica::class)->avaliarTipoDia('aracao', $talhao, today()->toDateString());

        $this->assertSame('indisponivel', $resultado['status']);
    }

    public function test_stale_weather_cache_never_returns_a_positive_recommendation(): void
    {
        $this->travelTo(today()->setTime(5, 0));
        $talhao = $this->talhao();
        $this->preverDia(today(), 5, 0.0);
        PrevisaoClimatica::query()->update(['atualizado_em' => now()->subHours(3)]);

        $resultado = app(InteligenciaClimatica::class)->avaliarTipoDia('pulverizacao', $talhao, today()->toDateString());

        $this->assertSame('indisponivel', $resultado['status']);
        $this->assertStringContainsString('desatualizados', $resultado['mensagem']);
    }

    public function test_liming_uses_soil_moisture_and_fails_closed_when_it_is_missing(): void
    {
        $this->travelTo(today()->setTime(5, 0));
        $talhao = $this->talhao();
        $this->preverDia(today(), 5, 0, 0.05);

        $seco = app(InteligenciaClimatica::class)->avaliarTipoDia('calagem', $talhao, today()->toDateString());
        $this->assertSame('atencao', $seco['status']);
        $this->assertStringContainsString('solo seco', $seco['mensagem']);

        PrevisaoClimatica::query()->delete();
        $this->preverDia(today(), 5, 0, null);
        $indisponivel = app(InteligenciaClimatica::class)->avaliarTipoDia('calagem', $talhao, today()->toDateString());
        $this->assertSame('indisponivel', $indisponivel['status']);
    }

    private function talhao(): Talhao
    {
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda climática', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53,
        ]);

        return $propriedade->talhoes()->create(['nome' => 'Talhão teste', 'area' => 10]);
    }

    private function preverDia(Carbon $dia, float $vento, float $chuva, ?float $umidadeSolo = 0.2): void
    {
        foreach (range(6, 17) as $hora) {
            $this->preverHora($dia, $hora, [
                'velocidade_vento' => $vento,
                'precipitacao' => $chuva,
                'duracao_sol' => 1800,
                'umidade_solo' => $umidadeSolo,
            ]);
        }
    }

    private function preverDiaCompleto(Carbon $dia, float $vento, float $chuva, ?float $umidadeSolo = 0.2): void
    {
        foreach (range(0, 23) as $hora) {
            $this->preverHora($dia, $hora, [
                'velocidade_vento' => $vento,
                'precipitacao' => $chuva,
                'duracao_sol' => 1800,
                'umidade_solo' => $umidadeSolo,
            ]);
        }
    }

    private function preverHora(Carbon $dia, int $hora, array $valores): void
    {
        PrevisaoClimatica::create([
            'talhao_id' => Talhao::query()->value('id'),
            'previsto_para' => $dia->copy()->setTime($hora, 0),
            'tipo_dado' => $dia->lt(today()) ? 'historico_estimado' : 'previsao',
            'temperatura' => 22,
            'umidade' => 65,
            'velocidade_vento' => 5,
            'rajada_vento' => 7,
            'precipitacao' => 0,
            'chance_chuva' => 0,
            'duracao_sol' => 1800,
            'umidade_solo' => 0.2,
            'evapotranspiracao' => 0.1,
            'codigo_tempo' => 1,
            'atualizado_em' => now(),
            ...$valores,
        ]);
    }
}

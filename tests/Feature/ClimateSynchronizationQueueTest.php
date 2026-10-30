<?php

namespace Tests\Feature;

use App\Jobs\SincronizarPrevisaoPropriedade;
use App\Models\PrevisaoClimatica;
use App\Models\Propriedade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ClimateSynchronizationQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_scheduler_command_dispatches_one_unique_job_per_property(): void
    {
        Bus::fake();
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda para fila', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53,
        ]);
        $propriedade->talhoes()->create(['nome' => 'Talhão 1', 'area' => 8]);

        $this->artisan('clima:atualizar-previsoes')->assertSuccessful();

        Bus::assertDispatched(SincronizarPrevisaoPropriedade::class, fn ($job) => $job->propriedadeId === $propriedade->id);
    }

    public function test_admin_manual_sync_queues_work_instead_of_fetching_weather_in_the_request(): void
    {
        Bus::fake();
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda manual', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53,
        ]);
        $propriedade->talhoes()->create(['nome' => 'Talhão 1', 'area' => 8]);

        $this->actingAs($admin)->post(route('admin.inteligencia.sincronizar'))
            ->assertRedirect()
            ->assertSessionHas('success')
            ->assertSessionHas('clima_sync_solicitada_em');

        Bus::assertDispatched(SincronizarPrevisaoPropriedade::class);
    }

    public function test_admin_can_poll_until_a_new_climate_forecast_is_persisted(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda status', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53,
        ]);
        $talhao = $propriedade->talhoes()->create(['nome' => 'Talhão 1', 'area' => 8]);
        PrevisaoClimatica::create([
            'talhao_id' => $talhao->id,
            'previsto_para' => now()->addHour(),
            'tipo_dado' => 'previsao',
            'atualizado_em' => now(),
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.inteligencia.sincronizacao.status', ['desde' => now()->subMinute()->toIso8601String()]))
            ->assertOk()
            ->assertJsonPath('atualizada', true);
    }
}

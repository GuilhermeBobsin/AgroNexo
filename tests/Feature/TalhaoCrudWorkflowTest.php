<?php

namespace Tests\Feature;

use App\Models\Cultura;
use App\Models\Propriedade;
use App\Models\Talhao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalhaoCrudWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_and_edit_forms_use_existing_routes_and_edit_persists_all_saved_fields(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda Talhões', 'localizacao' => 'PR', 'latitude' => -24.0, 'longitude' => -51.0,
        ]);
        $outraPropriedade = Propriedade::create([
            'nome' => 'Outra Fazenda', 'localizacao' => 'PR', 'latitude' => -24.1, 'longitude' => -51.1,
        ]);
        $cultura = Cultura::create(['nome' => 'Soja']);

        $this->actingAs($admin)->get(route('admin.propriedades.talhoes.create', $propriedade))->assertOk();

        $limiteInicial = [
            [-24.0000, -51.0000], [-24.0000, -50.9990], [-24.0010, -50.9990], [-24.0010, -51.0000],
        ];
        $this->postJson(route('admin.propriedades.talhoes.store', $propriedade), [
            'nome' => 'Talhão Norte', 'cultura_id' => $cultura->id, 'area' => 9999, 'limite' => json_encode($limiteInicial),
        ])->assertCreated()->assertJsonPath('redirect', route('admin.propriedades.talhoes.show', [$propriedade, 1]));

        $talhao = Talhao::firstOrFail();
        $this->assertSame('Talhão Norte', $talhao->nome);
        $this->assertSame($cultura->id, $talhao->cultura_id);
        $this->assertEquals($limiteInicial, $talhao->limite);
        $this->assertNotEquals(9999, (float) $talhao->area, 'A área deve ser recalculada no servidor a partir do polígono.');

        $this->get(route('admin.propriedades.talhoes.edit', [$propriedade, $talhao]))
            ->assertOk()->assertSee('Editar talhão')->assertSee('data-initial-limit', false);

        $limiteAtualizado = [
            [-24.0000, -51.0000], [-24.0000, -50.9980], [-24.0010, -50.9980], [-24.0010, -51.0000],
        ];
        $this->putJson(route('admin.propriedades.talhoes.update', [$propriedade, $talhao]), [
            'nome' => 'Talhão Norte Atualizado', 'cultura_id' => null, 'area' => 0.01, 'limite' => json_encode($limiteAtualizado),
        ])->assertOk()->assertJsonPath('redirect', route('admin.propriedades.talhoes.show', [$propriedade, $talhao]));

        $talhao->refresh();
        $this->assertSame('Talhão Norte Atualizado', $talhao->nome);
        $this->assertNull($talhao->cultura_id);
        $this->assertEquals($limiteAtualizado, $talhao->limite);
        $this->assertGreaterThan(0, (float) $talhao->area);
    }

    public function test_talhao_cannot_be_edited_through_an_unrelated_property_route(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda Original', 'localizacao' => 'PR', 'latitude' => -24, 'longitude' => -51,
        ]);
        $outraPropriedade = Propriedade::create([
            'nome' => 'Fazenda Alheia', 'localizacao' => 'PR', 'latitude' => -24, 'longitude' => -51,
        ]);
        $talhao = $propriedade->talhoes()->create([
            'nome' => 'Talhão 1', 'area' => 1, 'limite' => [[-24, -51], [-24, -50.999], [-24.001, -51]],
        ]);

        $this->actingAs($admin)
            ->get(route('admin.propriedades.talhoes.edit', [$outraPropriedade, $talhao]))
            ->assertNotFound();
    }

    public function test_talhao_map_flow_sends_user_to_property_edit_when_coordinates_are_missing(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Sem coordenadas', 'localizacao' => 'PR']);

        $this->actingAs($admin)
            ->get(route('admin.propriedades.talhoes.create', $propriedade))
            ->assertRedirect(route('admin.propriedades.edit', $propriedade))
            ->assertSessionHasErrors('coordenadas');
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\Cultura;
use App\Models\Propriedade;
use App\Models\Talhao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalhaoCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_talhao_list_create_and_detail_pages(): void
    {
        [$admin, $propriedade] = $this->adminAndProperty();
        $this->actingAs($admin);

        $this->get(route('admin.propriedades.talhoes.index', $propriedade))->assertOk()->assertSee('Talhões');
        $this->get(route('admin.propriedades.talhoes.create', $propriedade))->assertOk()->assertSee('Novo talhão');

        $talhao = $this->createTalhao($propriedade);
        $this->get(route('admin.propriedades.talhoes.show', [$propriedade, $talhao]))
            ->assertOk()->assertSee($talhao->nome)->assertSee('Limite do talhão');
        $this->get(route('admin.propriedades.talhoes.edit', [$propriedade, $talhao]))
            ->assertOk()->assertSee('Editar talhão')->assertSee($talhao->nome);
    }

    public function test_admin_can_create_and_update_talhao_geometry_using_the_schema_fields(): void
    {
        [$admin, $propriedade] = $this->adminAndProperty();
        $cultura = Cultura::create(['nome' => 'Soja']);
        $this->actingAs($admin);
        $limite = [[-29.0, -53.0], [-29.0, -53.1], [-29.1, -53.1]];

        $this->post(route('admin.propriedades.talhoes.store', $propriedade), [
            'nome' => 'Talhão A', 'cultura_id' => $cultura->id, 'area' => 12.5, 'limite' => json_encode($limite),
        ])->assertRedirect();
        $talhao = Talhao::where('nome', 'Talhão A')->firstOrFail();
        $this->assertEquals($limite, $talhao->limite);
        $this->assertGreaterThan(0, (float) $talhao->area);
        $this->assertNotEquals(12.5, (float) $talhao->area);

        $limiteAtualizado = [[-29.0, -53.0], [-29.0, -53.2], [-29.2, -53.2]];
        $this->put(route('admin.propriedades.talhoes.update', [$propriedade, $talhao]), [
            'nome' => 'Talhão A atualizado', 'cultura_id' => '', 'area' => 15, 'limite' => json_encode($limiteAtualizado),
        ])->assertRedirect(route('admin.propriedades.talhoes.show', [$propriedade, $talhao]));

        $this->assertDatabaseHas('talhoes', ['id' => $talhao->id, 'nome' => 'Talhão A atualizado', 'cultura_id' => null]);
        $this->assertNotEquals(15, (float) $talhao->fresh()->area);
        $this->assertEquals($limiteAtualizado, $talhao->fresh()->limite);
    }

    public function test_talhao_routes_do_not_expose_another_property_talhao(): void
    {
        [$admin, $propriedade] = $this->adminAndProperty();
        $outraPropriedade = Propriedade::create(['nome' => 'Outra', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53]);
        $talhao = $this->createTalhao($outraPropriedade);
        $this->actingAs($admin);

        $this->get(route('admin.propriedades.talhoes.show', [$propriedade, $talhao]))->assertNotFound();
        $this->put(route('admin.propriedades.talhoes.update', [$propriedade, $talhao]), [])->assertNotFound();
    }

    public function test_talhao_with_operational_history_cannot_be_deleted(): void
    {
        [$admin, $propriedade] = $this->adminAndProperty();
        $talhao = $this->createTalhao($propriedade);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $talhao->tarefas()->create([
            'propriedade_id' => $propriedade->id,
            'tipo' => 'outro',
            'titulo' => 'Atividade vinculada',
            'responsavel_id' => $operador->id,
            'data_prevista' => today(),
            'status' => 'pendente',
        ]);
        $this->actingAs($admin)->delete(route('admin.propriedades.talhoes.destroy', [$propriedade, $talhao]))
            ->assertRedirect(route('admin.propriedades.talhoes.index', $propriedade));
        $this->assertDatabaseHas('talhoes', ['id' => $talhao->id]);
    }

    private function adminAndProperty(): array
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda de teste', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53,
        ]);

        return [$admin, $propriedade];
    }

    private function createTalhao(Propriedade $propriedade): Talhao
    {
        return $propriedade->talhoes()->create([
            'nome' => 'Talhão teste', 'area' => 10, 'limite' => [[-29, -53], [-29, -53.1], [-29.1, -53.1]],
        ]);
    }
}

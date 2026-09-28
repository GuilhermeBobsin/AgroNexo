<?php

namespace Tests\Feature;

use App\Models\Propriedade;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoleWorkflowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_agronomo_without_removing_other_property_users(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $agronomo = User::factory()->create(['perfil' => 'agronomo', 'status' => 'ativo']);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Norte', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);

        $response = $this->actingAs($admin)->put(route('admin.propriedades.agronomos.update', $propriedade), [
            'agronomo_ids' => [$agronomo->id],
        ]);

        $response->assertRedirect(route('admin.propriedades.show', $propriedade));
        $this->assertDatabaseHas('propriedade_usuario', [
            'propriedade_id' => $propriedade->id,
            'usuario_id' => $agronomo->id,
            'papel' => 'agronomo',
        ]);
        $this->assertDatabaseHas('propriedade_usuario', [
            'propriedade_id' => $propriedade->id,
            'usuario_id' => $operador->id,
            'papel' => 'colaborador',
        ]);
    }

    public function test_agronomo_can_only_open_tasks_for_linked_properties(): void
    {
        $agronomo = User::factory()->create(['perfil' => 'agronomo', 'status' => 'ativo']);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $permitida = Propriedade::create(['nome' => 'Fazenda Vinculada', 'localizacao' => 'RS']);
        $naoPermitida = Propriedade::create(['nome' => 'Fazenda Externa', 'localizacao' => 'SC']);
        $permitida->usuarios()->attach($agronomo->id, ['papel' => 'agronomo']);

        $tarefaPermitida = $this->criarTarefa($permitida, $operador, 'Atividade autorizada');
        $tarefaExterna = $this->criarTarefa($naoPermitida, $operador, 'Atividade externa');

        $this->actingAs($agronomo)
            ->get(route('agronomo.tarefas.show', $tarefaPermitida))
            ->assertOk();
        $this->actingAs($agronomo)
            ->get(route('agronomo.tarefas.show', $tarefaExterna))
            ->assertNotFound();
        $this->actingAs($agronomo)->get(route('agronomo.dashboard'))
            ->assertOk()->assertSee('acompanhamento técnico')->assertDontSee('Nova tarefa');
    }

    public function test_operator_can_advance_an_assigned_task_and_cannot_update_another_operators_task(): void
    {
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $outroOperador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Execução', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);
        $propriedade->usuarios()->attach($outroOperador->id, ['papel' => 'colaborador']);
        $tarefa = $this->criarTarefa($propriedade, $operador, 'Tarefa do operador');
        $tarefaAlheia = $this->criarTarefa($propriedade, $outroOperador, 'Tarefa de outro operador');

        $this->actingAs($operador)
            ->patch(route('operador.tarefas.status', $tarefa), ['status' => 'em_andamento'])
            ->assertRedirect();
        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'status' => 'em_andamento']);

        $this->patch(route('operador.tarefas.status', $tarefaAlheia), ['status' => 'em_andamento'])
            ->assertForbidden();
        $this->assertDatabaseHas('tarefas', ['id' => $tarefaAlheia->id, 'status' => 'pendente']);
    }

    public function test_operator_completion_records_application_and_decrements_property_stock(): void
    {
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Aplicação', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);
        $talhaoId = DB::table('talhoes')->insertGetId([
            'propriedade_id' => $propriedade->id,
            'nome' => 'Talhão 1',
            'area' => 12,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $produtoId = DB::table('produtos')->insertGetId([
            'nome' => 'Insumo teste',
            'unidade' => 'L',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('produto_propriedade')->insert([
            'produto_id' => $produtoId,
            'propriedade_id' => $propriedade->id,
            'estoque_atual' => 10,
            'estoque_minimo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $tarefa = Tarefa::create([
            'propriedade_id' => $propriedade->id,
            'talhao_id' => $talhaoId,
            'tipo' => 'aplicacao',
            'titulo' => 'Aplicar insumo',
            'responsavel_id' => $operador->id,
            'produto_id' => $produtoId,
            'dose' => 3,
            'data_prevista' => today(),
            'status' => 'em_andamento',
        ]);

        $this->actingAs($operador)->patch(route('operador.tarefas.status', $tarefa), [
            'status' => 'concluida',
            'dose_realizada' => 2.5,
            'temperatura' => 24,
            'umidade' => 65,
        ])->assertRedirect();

        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'status' => 'concluida']);
        $this->assertDatabaseHas('aplicacoes', [
            'tarefa_id' => $tarefa->id,
            'usuario_id' => $operador->id,
            'status' => 'realizada',
            'dose' => 2.5,
        ]);
        $this->assertDatabaseHas('produto_propriedade', [
            'produto_id' => $produtoId,
            'propriedade_id' => $propriedade->id,
            'estoque_atual' => 7.5,
        ]);
    }

    public function test_admin_and_operator_dashboards_render_operational_data(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Painel', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);
        $this->criarTarefa($propriedade, $operador, 'Tarefa do painel');

        $this->actingAs($admin)->get(route('admin.dashboard', [
            'propriedade_id' => $propriedade->id,
            'responsavel_id' => $operador->id,
            'data_inicio' => today()->toDateString(),
            'data_fim' => today()->toDateString(),
        ]))->assertOk()->assertSee('Painel administrativo')->assertSee('Tarefa do painel')->assertSee('Resumo por propriedade');
        $this->actingAs($operador)->get(route('dashboard.operador'))
            ->assertOk()->assertSee('Minhas tarefas')->assertSee('Tarefa do painel');
    }

    private function criarTarefa(Propriedade $propriedade, User $responsavel, string $titulo): Tarefa
    {
        return Tarefa::create([
            'propriedade_id' => $propriedade->id,
            'tipo' => 'outro',
            'titulo' => $titulo,
            'responsavel_id' => $responsavel->id,
            'data_prevista' => today(),
            'status' => 'pendente',
        ]);
    }
}

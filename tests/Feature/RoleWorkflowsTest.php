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
        $this->get(route('operador.tarefas.show', $tarefa))->assertOk()->assertSee('Cancelar tarefa');
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
        $this->assertDatabaseHas('movimentacoes_estoque', [
            'produto_propriedade_id' => DB::table('produto_propriedade')->value('id'),
            'tarefa_id' => $tarefa->id,
            'usuario_id' => $operador->id,
            'tipo' => 'saida',
            'quantidade' => 2.5,
            'saldo_anterior' => 10,
            'saldo_atual' => 7.5,
        ]);
    }

    public function test_insufficient_stock_rolls_back_task_completion_application_and_ledger(): void
    {
        [$operador, $tarefa, $produtoId, $propriedade] = $this->tarefaDeAplicacao(1, 2);

        $this->actingAs($operador)->patch(route('operador.tarefas.status', $tarefa), [
            'status' => 'concluida', 'dose_realizada' => 2,
        ])->assertUnprocessable();

        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'status' => 'em_andamento', 'data_conclusao' => null]);
        $this->assertDatabaseHas('produto_propriedade', ['produto_id' => $produtoId, 'propriedade_id' => $propriedade->id, 'estoque_atual' => 1]);
        $this->assertDatabaseCount('aplicacoes', 0);
        $this->assertDatabaseCount('movimentacoes_estoque', 0);
    }

    public function test_operator_cannot_start_a_task_with_an_unavailable_resource(): void
    {
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Recurso', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);
        $recursoId = DB::table('recursos')->insertGetId([
            'propriedade_id' => $propriedade->id, 'nome' => 'Pulverizador em manutenção', 'tipo' => 'pulverizador',
            'status' => 'manutencao', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $tarefa = $this->criarTarefa($propriedade, $operador, 'Pulverizar talhão');
        $tarefa->update(['recurso_id' => $recursoId]);

        $this->actingAs($operador)->patch(route('operador.tarefas.status', $tarefa), ['status' => 'em_andamento'])
            ->assertUnprocessable();
        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'status' => 'pendente']);
        $this->assertDatabaseHas('recursos', ['id' => $recursoId, 'status' => 'manutencao']);
    }

    public function test_operator_cancellation_requires_and_records_reason_and_releases_resource(): void
    {
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Cancelamento', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);
        $recursoId = DB::table('recursos')->insertGetId([
            'propriedade_id' => $propriedade->id, 'nome' => 'Trator', 'tipo' => 'trator', 'status' => 'em_uso',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $tarefa = $this->criarTarefa($propriedade, $operador, 'Aração');
        $tarefa->update(['recurso_id' => $recursoId, 'status' => 'em_andamento']);

        $this->actingAs($operador)->patch(route('operador.tarefas.status', $tarefa), ['status' => 'cancelada'])
            ->assertSessionHasErrors('motivo_cancelamento');
        $this->patch(route('operador.tarefas.status', $tarefa), [
            'status' => 'cancelada', 'motivo_cancelamento' => 'Chuva forte no talhão',
        ])->assertRedirect();

        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefa->id, 'status' => 'cancelada', 'motivo_cancelamento' => 'Chuva forte no talhão',
        ]);
        $this->assertDatabaseHas('recursos', ['id' => $recursoId, 'status' => 'disponivel']);
    }

    public function test_admin_can_cancel_pending_task_with_audited_reason(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Cancelamento Admin', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);
        $tarefa = $this->criarTarefa($propriedade, $operador, 'Manutenção planejada');

        $this->actingAs($admin)->patch(route('admin.tarefas.cancelar', $tarefa), [
            'motivo_cancelamento' => 'Replanejamento da operação',
        ])->assertRedirect();

        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefa->id, 'status' => 'cancelada', 'motivo_cancelamento' => 'Replanejamento da operação',
        ]);
    }

    public function test_task_assignment_start_edit_lock_and_admin_cancellation_keep_resource_state_consistent(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Estado', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);

        $this->actingAs($admin)->post(route('admin.recursos.store'), [
            'propriedade_id' => $propriedade->id,
            'nome' => 'Trator compartilhado',
            'tipo' => 'trator',
            'status' => 'disponivel',
        ])->assertRedirect();
        $recursoId = DB::table('recursos')->value('id');

        $this->post(route('admin.tarefas.store'), [
            'propriedade_id' => $propriedade->id,
            'tipo' => 'aracao',
            'titulo' => 'Preparar talhão',
            'responsavel_id' => $operador->id,
            'recurso_id' => $recursoId,
            'data_prevista' => today()->toDateString(),
        ])->assertRedirect();
        $tarefa = Tarefa::firstOrFail();

        $this->actingAs($operador)->patch(route('operador.tarefas.status', $tarefa), ['status' => 'em_andamento'])
            ->assertRedirect();
        $this->assertDatabaseHas('recursos', ['id' => $recursoId, 'status' => 'em_uso']);
        $this->actingAs($admin)->get(route('admin.tarefas.edit', $tarefa))->assertForbidden();

        $this->put(route('admin.recursos.update', $recursoId), [
            'propriedade_id' => $propriedade->id, 'nome' => 'Trator compartilhado',
            'tipo' => 'trator', 'status' => 'manutencao',
        ])->assertRedirect()->assertSessionHas('error');
        $this->assertDatabaseHas('recursos', ['id' => $recursoId, 'status' => 'em_uso']);

        $this->patch(route('admin.tarefas.cancelar', $tarefa), [
            'motivo_cancelamento' => 'Operação reprogramada por chuva',
        ])->assertRedirect();
        $this->get(route('admin.tarefas.show', $tarefa))->assertOk()->assertSee('Operação reprogramada por chuva');
        $this->assertDatabaseHas('recursos', ['id' => $recursoId, 'status' => 'disponivel']);
        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'status' => 'cancelada']);
    }

    private function tarefaDeAplicacao(float $saldo, float $dose): array
    {
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Estoque', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);
        $talhaoId = DB::table('talhoes')->insertGetId([
            'propriedade_id' => $propriedade->id, 'nome' => 'Talhão 1', 'area' => 12,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $produtoId = DB::table('produtos')->insertGetId([
            'nome' => 'Insumo teste', 'unidade' => 'L', 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('produto_propriedade')->insert([
            'produto_id' => $produtoId, 'propriedade_id' => $propriedade->id,
            'estoque_atual' => $saldo, 'estoque_minimo' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $tarefa = Tarefa::create([
            'propriedade_id' => $propriedade->id, 'talhao_id' => $talhaoId, 'tipo' => 'aplicacao',
            'titulo' => 'Aplicar produto', 'responsavel_id' => $operador->id, 'produto_id' => $produtoId,
            'dose' => $dose, 'data_prevista' => today(), 'status' => 'em_andamento',
        ]);

        return [$operador, $tarefa, $produtoId, $propriedade];
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

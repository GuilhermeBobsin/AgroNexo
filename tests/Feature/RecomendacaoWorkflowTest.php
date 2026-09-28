<?php

namespace Tests\Feature;

use App\Models\Propriedade;
use App\Models\Recomendacao;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RecomendacaoWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_agronomo_recommendation_can_be_reviewed_and_converted_to_an_operator_task(): void
    {
        [$agronomo, $admin, $operador, $propriedade] = $this->criarEquipe();
        $talhaoId = DB::table('talhoes')->insertGetId([
            'propriedade_id' => $propriedade->id,
            'nome' => 'Talhão A',
            'area' => 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $produtoId = DB::table('produtos')->insertGetId([
            'nome' => 'Produto recomendado',
            'unidade' => 'L',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('produto_propriedade')->insert([
            'produto_id' => $produtoId,
            'propriedade_id' => $propriedade->id,
            'estoque_atual' => 12,
            'estoque_minimo' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs($agronomo)->get(route('agronomo.recomendacoes.create'))
            ->assertOk()->assertSee('Nova recomendação técnica')->assertSee('Produto recomendado');

        $this->actingAs($agronomo)->post(route('agronomo.recomendacoes.store'), [
            'propriedade_id' => $propriedade->id,
            'talhao_id' => $talhaoId,
            'titulo' => 'Controle preventivo da doença',
            'tipo' => 'aplicacao',
            'prioridade' => 'alta',
            'diagnostico' => 'Foi observado aumento de pressão da doença no talhão.',
            'orientacao' => 'Aplicar o produto recomendado no talhão conforme a dose indicada.',
            'produto_id' => $produtoId,
            'dose' => 2.5,
        ])->assertRedirect();

        $recomendacao = Recomendacao::firstOrFail();
        $this->assertSame('pendente', $recomendacao->status);
        $this->assertSame($agronomo->id, $recomendacao->agronomo_id);
        $this->actingAs($admin)->get(route('admin.recomendacoes.index'))
            ->assertOk()->assertSee('Controle preventivo da doença');
        $this->get(route('admin.recomendacoes.show', $recomendacao))
            ->assertOk()->assertSee('Aprovar e criar tarefa');

        $this->actingAs($admin)->patch(route('admin.recomendacoes.criar-tarefa', $recomendacao), [
            'responsavel_id' => $operador->id,
            'data_prevista' => today()->addDay()->toDateString(),
            'hora_prevista' => '08:30',
            'parecer_admin' => 'Aprovada após revisão técnica.',
        ])->assertRedirect();

        $this->assertDatabaseHas('recomendacoes', [
            'id' => $recomendacao->id,
            'status' => 'tarefa_criada',
            'analisado_por' => $admin->id,
        ]);
        $tarefa = Tarefa::where('recomendacao_id', $recomendacao->id)->firstOrFail();
        $this->assertSame('aplicacao', $tarefa->tipo);
        $this->assertSame($operador->id, $tarefa->responsavel_id);
        $this->assertSame($produtoId, $tarefa->produto_id);
        $this->assertSame('2.500', $tarefa->dose);
        $this->assertStringContainsString('Diagnóstico:', $tarefa->observacoes);

        $this->actingAs($agronomo)->get(route('agronomo.recomendacoes.show', $recomendacao))
            ->assertOk()->assertSee('Parecer do administrador')->assertSee('Tarefa criada');
    }

    public function test_admin_can_reject_recommendation_with_a_reason_visible_to_the_agronomo(): void
    {
        [$agronomo, $admin, , $propriedade] = $this->criarEquipe();
        $recomendacao = Recomendacao::create([
            'propriedade_id' => $propriedade->id,
            'agronomo_id' => $agronomo->id,
            'titulo' => 'Avaliar irrigação',
            'tipo' => 'irrigacao',
            'prioridade' => 'normal',
            'diagnostico' => 'A umidade observada está abaixo do valor esperado.',
            'orientacao' => 'Revisar o cronograma de irrigação antes de alterar a operação.',
        ]);

        $this->actingAs($admin)->patch(route('admin.recomendacoes.recusar', $recomendacao), [
            'parecer_admin' => 'Aguardaremos a próxima leitura de umidade.',
        ])->assertRedirect();

        $this->assertDatabaseHas('recomendacoes', [
            'id' => $recomendacao->id,
            'status' => 'recusada',
            'analisado_por' => $admin->id,
        ]);
        $this->actingAs($agronomo)->get(route('agronomo.recomendacoes.show', $recomendacao))
            ->assertOk()->assertSee('Aguardaremos a próxima leitura de umidade.');
        $this->assertDatabaseCount('tarefas', 0);
    }

    public function test_agronomo_cannot_recommend_for_an_unassigned_property(): void
    {
        [$agronomo, , ,] = $this->criarEquipe();
        $externa = Propriedade::create(['nome' => 'Propriedade externa', 'localizacao' => 'SC']);

        $this->actingAs($agronomo)->post(route('agronomo.recomendacoes.store'), [
            'propriedade_id' => $externa->id,
            'titulo' => 'Recomendação fora do escopo',
            'tipo' => 'outro',
            'prioridade' => 'normal',
            'diagnostico' => 'O diagnóstico possui detalhes suficientes para teste.',
            'orientacao' => 'A orientação técnica possui detalhes suficientes para teste.',
        ])->assertSessionHasErrors('propriedade_id');

        $this->assertDatabaseCount('recomendacoes', 0);
    }

    public function test_deleting_a_pending_task_created_from_a_recommendation_returns_it_to_review(): void
    {
        [$agronomo, $admin, $operador, $propriedade] = $this->criarEquipe();
        $recomendacao = Recomendacao::create([
            'propriedade_id' => $propriedade->id,
            'agronomo_id' => $agronomo->id,
            'titulo' => 'Revisar drenagem',
            'tipo' => 'manutencao',
            'prioridade' => 'normal',
            'diagnostico' => 'A água está acumulando no trecho baixo do talhão.',
            'orientacao' => 'Avaliar e corrigir os canais de drenagem no trecho indicado.',
            'status' => 'tarefa_criada',
            'analisado_por' => $admin->id,
            'analisado_em' => now(),
        ]);
        $tarefa = Tarefa::create([
            'recomendacao_id' => $recomendacao->id,
            'propriedade_id' => $propriedade->id,
            'tipo' => 'manutencao',
            'titulo' => $recomendacao->titulo,
            'responsavel_id' => $operador->id,
            'data_prevista' => today()->addDay(),
            'status' => 'pendente',
        ]);

        $this->actingAs($admin)->delete(route('admin.tarefas.destroy', $tarefa))->assertRedirect();

        $this->assertDatabaseHas('recomendacoes', [
            'id' => $recomendacao->id,
            'status' => 'pendente',
            'analisado_por' => null,
        ]);
        $this->assertDatabaseMissing('tarefas', ['id' => $tarefa->id]);
    }

    private function criarEquipe(): array
    {
        $agronomo = User::factory()->create(['perfil' => 'agronomo', 'status' => 'ativo']);
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda Recomendações', 'localizacao' => 'RS']);
        $propriedade->usuarios()->attach($agronomo->id, ['papel' => 'agronomo']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);

        return [$agronomo, $admin, $operador, $propriedade];
    }
}

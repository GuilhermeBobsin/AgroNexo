<?php

namespace Tests\Feature;

use App\Models\Propriedade;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminInventoryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_creation_adjustment_history_and_delete_protection_work_together(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda de estoque', 'localizacao' => 'RS']);
        $produto = Produto::create(['nome' => 'Fertilizante teste', 'unidade' => 'kg']);
        $this->actingAs($admin);

        $this->postJson(route('admin.estoque.store'), [
            'produto_id' => $produto->id,
            'propriedade_id' => $propriedade->id,
            'estoque_atual' => '10.000',
            'estoque_minimo' => '2.000',
        ])->assertCreated();
        $estoque = DB::table('produto_propriedade')->first();
        $this->assertDatabaseHas('movimentacoes_estoque', [
            'produto_propriedade_id' => $estoque->id,
            'tipo' => 'entrada',
            'quantidade' => 10,
            'saldo_anterior' => 0,
            'saldo_atual' => 10,
            'usuario_id' => $admin->id,
        ]);

        $this->putJson(route('admin.estoque.update', $estoque->id), [
            'produto_id' => $produto->id,
            'propriedade_id' => $propriedade->id,
            'estoque_atual' => '15.000',
            'estoque_minimo' => '3.000',
            'motivo_ajuste' => 'Entrada de compra conferida',
        ])->assertOk();
        $this->assertDatabaseHas('movimentacoes_estoque', [
            'produto_propriedade_id' => $estoque->id,
            'tipo' => 'ajuste',
            'quantidade' => 5,
            'saldo_anterior' => 10,
            'saldo_atual' => 15,
            'motivo' => 'Entrada de compra conferida',
        ]);

        $this->get(route('admin.estoque.show', $estoque->id))
            ->assertOk()->assertSee('Livro de movimentações')->assertSee('Entrada de compra conferida');
        $this->deleteJson(route('admin.estoque.destroy', $estoque->id))->assertStatus(409);
        $this->assertDatabaseHas('produto_propriedade', ['id' => $estoque->id, 'estoque_atual' => 15]);
        $this->deleteJson(route('admin.produtos.destroy', $produto))->assertStatus(409);
    }

    public function test_adjusting_stock_quantity_requires_a_reason(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create(['nome' => 'Fazenda motivo', 'localizacao' => 'RS']);
        $produto = Produto::create(['nome' => 'Semente teste', 'unidade' => 'kg']);
        $id = DB::table('produto_propriedade')->insertGetId([
            'produto_id' => $produto->id, 'propriedade_id' => $propriedade->id,
            'estoque_atual' => 8, 'estoque_minimo' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($admin)->putJson(route('admin.estoque.update', $id), [
            'produto_id' => $produto->id, 'propriedade_id' => $propriedade->id,
            'estoque_atual' => '7.000', 'estoque_minimo' => '1.000',
        ])->assertUnprocessable()->assertJsonValidationErrors('motivo_ajuste');

        $this->assertDatabaseHas('produto_propriedade', ['id' => $id, 'estoque_atual' => 8]);
        $this->assertDatabaseCount('movimentacoes_estoque', 0);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Propriedade;
use App\Models\Produto;
use App\Models\Cultura;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_producer_admin_only_sees_and_manages_their_own_farm_data(): void
    {
        $this->post('/register', [
            'name' => 'Produtor A', 'email' => 'a@example.test',
            'password' => 'SenhaSegura123!', 'password_confirmation' => 'SenhaSegura123!',
            'propriedade_nome' => 'Fazenda A', 'propriedade_localizacao' => 'PR',
        ])->assertRedirect(route('dashboard.admin'));
        $adminA = User::where('email', 'a@example.test')->firstOrFail();
        $fazendaA = $adminA->propriedades()->firstOrFail();
        $produtoA = Produto::create(['nome' => 'Produto privado A', 'unidade' => 'L']);
        $produtoA->propriedades()->attach($fazendaA->id);
        $culturaA = Cultura::create(['nome' => 'Cultura exclusiva A']);

        auth()->logout();
        $this->post('/register', [
            'name' => 'Produtor B', 'email' => 'b@example.test',
            'password' => 'SenhaSegura123!', 'password_confirmation' => 'SenhaSegura123!',
            'propriedade_nome' => 'Fazenda B', 'propriedade_localizacao' => 'SC',
        ])->assertRedirect(route('dashboard.admin'));
        $adminB = User::where('email', 'b@example.test')->firstOrFail();
        $fazendaB = $adminB->propriedades()->firstOrFail();
        $produtoB = Produto::create(['nome' => 'Produto privado B', 'unidade' => 'kg']);
        $produtoB->propriedades()->attach($fazendaB->id);
        $culturaB = Cultura::create(['nome' => 'Cultura exclusiva B']);

        $this->actingAs($adminA)->get(route('admin.propriedades.index'))
            ->assertOk()->assertSee('Fazenda A')->assertDontSee('Fazenda B');
        $this->get(route('admin.propriedades.show', $fazendaB))->assertNotFound();
        $this->get(route('admin.produtos.index'))->assertOk()
            ->assertSee('Produto privado A')->assertDontSee('Produto privado B');
        $this->get(route('admin.produtos.show', $produtoB))->assertNotFound();
        $this->get(route('admin.culturas.index'))->assertOk()->assertSee('Cultura exclusiva A')->assertDontSee('Cultura exclusiva B');
        $this->put(route('admin.culturas.update', $culturaB), ['nome' => 'Cultura invadida'])->assertNotFound();
        $this->post(route('admin.usuarios.store'), [
            'name' => 'Agrônomo indevido', 'email' => 'intruso@example.test',
            'password' => 'SenhaSegura123!', 'perfil' => 'agronomo',
            'propriedade_ids' => [$fazendaB->id],
        ])->assertSessionHasErrors('propriedade_ids.0');
        $this->assertDatabaseMissing('users', ['email' => 'intruso@example.test']);
    }
}

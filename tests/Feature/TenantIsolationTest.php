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

    public function test_admin_can_load_a_new_session_without_recursing_through_the_user_scope(): void
    {
        $this->post('/register', [
            'email' => 'sessao@example.test',
            'password' => 'SenhaSegura123!',
        ])->assertRedirect(route('admin.dashboard'));

        // Simulate the next HTTP request, where the guard must rehydrate its user from session.
        app('auth')->forgetGuards();

        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_each_producer_admin_only_sees_and_manages_their_own_farm_data(): void
    {
        $this->post('/register', [
            'email' => 'a@example.test',
            'password' => 'SenhaSegura123!',
        ])->assertRedirect(route('admin.dashboard'));
        $adminA = User::where('email', 'a@example.test')->firstOrFail();
        $this->post(route('admin.propriedades.store'), [
            'nome' => 'Fazenda A', 'localizacao' => 'PR', 'latitude' => -25.4, 'longitude' => -49.2,
        ])->assertRedirect();
        $fazendaA = $adminA->propriedades()->firstOrFail();
        $this->postJson(route('admin.culturas.store'), ['nome' => 'Milho'])->assertCreated();
        $this->assertDatabaseHas('culturas', ['nome' => 'Milho', 'admin_id' => $adminA->id]);
        $produtoA = Produto::create(['nome' => 'Produto privado A', 'unidade' => 'L']);
        $produtoA->propriedades()->attach($fazendaA->id);
        $culturaA = Cultura::create(['nome' => 'Cultura exclusiva A']);

        auth()->logout();
        $this->post('/register', [
            'email' => 'b@example.test',
            'password' => 'SenhaSegura123!',
        ])->assertRedirect(route('admin.dashboard'));
        $adminB = User::where('email', 'b@example.test')->firstOrFail();
        $this->post(route('admin.propriedades.store'), [
            'nome' => 'Fazenda B', 'localizacao' => 'SC', 'latitude' => -27.5, 'longitude' => -48.5,
        ])->assertRedirect();
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

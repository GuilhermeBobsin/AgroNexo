<?php

namespace Tests\Feature;

use App\Models\Propriedade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_primary_crud_lists_and_forms_with_empty_data(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda Base', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53,
        ]);

        $routes = [
            'admin.propriedades.index' => [],
            'admin.propriedades.create' => [],
            'admin.propriedades.show' => [$propriedade],
            'admin.propriedades.talhoes.index' => [$propriedade],
            'admin.propriedades.talhoes.create' => [$propriedade],
            'admin.culturas.index' => [],
            'admin.produtos.index' => [],
            'admin.produtos.create' => [],
            'admin.estoque.index' => [],
            'admin.estoque.create' => [],
            'admin.recursos.index' => [],
            'admin.recursos.create' => [],
            'admin.tarefas.index' => [],
            'admin.tarefas.create' => [],
            'admin.usuarios.index' => [],
            'admin.usuarios.create' => [],
            'admin.aplicacoes.index' => [],
            'admin.recomendacoes.index' => [],
            'admin.inteligencia.index' => [],
        ];

        $this->actingAs($admin);
        foreach ($routes as $route => $parameters) {
            $this->get(route($route, $parameters))->assertOk();
        }
    }

    public function test_agronomo_and_operator_can_open_their_workflows_but_not_admin_cruds(): void
    {
        $agronomo = User::factory()->create(['perfil' => 'agronomo', 'status' => 'ativo']);
        $operador = User::factory()->create(['perfil' => 'operador', 'status' => 'ativo']);
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $propriedade = Propriedade::create([
            'nome' => 'Fazenda Compartilhada', 'localizacao' => 'RS', 'latitude' => -29, 'longitude' => -53,
        ]);
        $propriedade->usuarios()->attach($agronomo->id, ['papel' => 'agronomo']);
        $propriedade->usuarios()->attach($operador->id, ['papel' => 'colaborador']);

        foreach (['agronomo.dashboard', 'agronomo.tarefas.index', 'agronomo.recomendacoes.index', 'agronomo.recomendacoes.create', 'agronomo.aplicacoes.index', 'agronomo.inteligencia.index'] as $route) {
            $this->actingAs($agronomo)->get(route($route))->assertOk();
        }
        foreach (['operador.dashboard', 'operador.tarefas.index'] as $route) {
            $this->actingAs($operador)->get(route($route))->assertOk();
        }

        $this->actingAs($agronomo)->get(route('admin.produtos.index'))->assertRedirect(route('dashboard.agronomo'));
        $this->actingAs($operador)->get(route('admin.estoque.index'))->assertRedirect(route('dashboard.operador'));
        $this->actingAs($operador)->get(route('agronomo.recomendacoes.index'))->assertRedirect(route('dashboard.operador'));
        $this->actingAs($admin)->get(route('operador.tarefas.index'))->assertRedirect(route('dashboard.admin'));
    }
}

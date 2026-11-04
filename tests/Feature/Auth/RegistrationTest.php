<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_producer_can_register_as_admin_and_create_their_property(): void
    {
        $this->get('/register')->assertOk()->assertSee('Cadastre sua conta e fazenda');

        $this->post('/register', [
            'name' => 'Produtor Teste',
            'email' => 'produtor@example.test',
            'password' => 'SenhaSegura123!',
            'password_confirmation' => 'SenhaSegura123!',
            'propriedade_nome' => 'Fazenda Exemplo',
            'propriedade_localizacao' => 'Cascavel, PR',
        ])->assertRedirect(route('dashboard.admin'));

        $user = User::where('email', 'produtor@example.test')->firstOrFail();
        $this->assertSame('admin', $user->perfil);
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('propriedades', ['nome' => 'Fazenda Exemplo', 'localizacao' => 'Cascavel, PR']);
        $this->assertDatabaseHas('propriedade_usuario', ['usuario_id' => $user->id, 'papel' => 'dono']);
    }

    public function test_registration_is_transactional_and_requires_property_and_strong_password(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'propriedade_nome' => '',
            'propriedade_localizacao' => '',
        ])->assertSessionHasErrors(['password', 'propriedade_nome', 'propriedade_localizacao']);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_database_defaults_new_users_to_an_active_operator(): void
    {
        $user = User::create([
            'name' => 'Conta interna',
            'email' => 'interna@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->assertSame('operador', $user->fresh()->perfil);
        $this->assertSame('ativo', $user->fresh()->status);
    }
}

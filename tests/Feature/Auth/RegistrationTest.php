<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_producer_can_register_as_admin_with_only_email_and_password(): void
    {
        $this->get('/register')->assertOk()->assertSee('Crie sua conta')->assertDontSee('Nome da fazenda')->assertDontSee('Município e estado');

        $this->post('/register', [
            'email' => 'produtor@example.test',
            'password' => 'SenhaSegura123!',
        ])->assertRedirect(route('dashboard.admin'));

        $user = User::where('email', 'produtor@example.test')->firstOrFail();
        $this->assertSame('admin', $user->perfil);
        $this->assertAuthenticatedAs($user);
        $this->assertSame('Produtor', $user->name);
        $this->assertDatabaseCount('propriedades', 0);
        $this->assertDatabaseMissing('propriedade_usuario', ['usuario_id' => $user->id]);
    }

    public function test_registration_requires_valid_email_and_strong_password(): void
    {
        $this->post('/register', [
            'email' => '',
            'password' => 'password',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_new_admin_can_log_in_after_signing_up_without_a_property(): void
    {
        $credentials = ['email' => 'novo-admin@example.test', 'password' => 'SenhaSegura123!'];

        $this->post('/register', $credentials)->assertRedirect(route('dashboard.admin'));
        $this->post('/logout')->assertRedirect('/');

        $this->post('/login', $credentials)->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();
        $this->get(route('dashboard.admin'))->assertOk();
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

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InitialAdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_not_available_and_web_user_creation_cannot_assign_admin(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [])->assertNotFound();

        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);
        $this->actingAs($admin)->post(route('admin.usuarios.store'), [
            'name' => 'Conta indevida',
            'email' => 'elevacao@example.test',
            'password' => 'senha-segura-123',
            'perfil' => 'admin',
        ])->assertSessionHasErrors('perfil');

        $this->assertDatabaseMissing('users', ['email' => 'elevacao@example.test']);
    }

    public function test_initial_admin_can_be_created_once_through_interactive_server_setup(): void
    {
        $this->artisan('agronexo:setup-admin')
            ->expectsQuestion('Nome do administrador', 'Admin inicial')
            ->expectsQuestion('E-mail', 'admin@example.test')
            ->expectsQuestion('Senha (mínimo de 12 caracteres)', 'UmaSenhaMuitoSegura123!')
            ->expectsQuestion('Confirme a senha', 'UmaSenhaMuitoSegura123!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => 'admin@example.test', 'perfil' => 'admin', 'status' => 'ativo']);

        $this->artisan('agronexo:setup-admin')->assertExitCode(1);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_last_admin_cannot_delete_account_through_profile_endpoint(): void
    {
        $admin = User::factory()->create(['perfil' => 'admin', 'status' => 'ativo']);

        $this->actingAs($admin)->delete(route('profile.destroy'), [
            'password' => 'password',
        ])->assertSessionHasErrors('userDeletion.password');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_area_requires_admin_profile_and_inactive_account_is_blocked(): void
    {
        $agronomo = User::factory()->create(['perfil' => 'agronomo', 'status' => 'ativo']);
        $this->actingAs($agronomo)->get(route('admin.usuarios.index'))->assertRedirect(route('dashboard.agronomo'));

        $inactiveAdmin = User::factory()->create(['perfil' => 'admin', 'status' => 'inativo']);
        $this->actingAs($inactiveAdmin)->get(route('admin.usuarios.index'))->assertForbidden();
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_public_registration_cannot_create_an_administrator(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertNotFound();
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

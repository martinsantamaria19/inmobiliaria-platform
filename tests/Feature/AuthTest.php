<?php

namespace Tests\Feature;

use App\Models\Inmobiliaria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_puede_hacer_login(): void
    {
        $superadmin = User::factory()->superadmin()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($superadmin);
    }

    public function test_superadmin_es_redirigido_al_panel_admin(): void
    {
        $superadmin = User::factory()->superadmin()->create();

        $response = $this->actingAs($superadmin)->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_inmobiliaria_aprobada_puede_hacer_login(): void
    {
        $user = User::factory()->inmobiliaria()->create([
            'email' => 'inmo@test.com',
            'password' => bcrypt('password123'),
        ]);
        Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $response = $this->post('/login', [
            'email' => 'inmo@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_inmobiliaria_es_redirigida_al_panel(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('panel.dashboard'));
    }

    public function test_login_con_credenciales_incorrectas_falla(): void
    {
        User::factory()->create([
            'email' => 'user@test.com',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_con_email_inexistente_falla(): void
    {
        $response = $this->post('/login', [
            'email' => 'noexiste@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_usuario_puede_hacer_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}

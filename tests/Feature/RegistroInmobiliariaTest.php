<?php

namespace Tests\Feature;

use App\Models\Inmobiliaria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroInmobiliariaTest extends TestCase
{
    use RefreshDatabase;

    public function test_formulario_de_registro_carga_correctamente(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
    }

    public function test_registro_crea_user_con_role_inmobiliaria(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'nueva@inmo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'nueva@inmo.com',
            'role' => 'inmobiliaria',
        ]);
    }

    public function test_registro_crea_registro_inmobiliaria_pendiente(): void
    {
        $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'nueva@inmo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'nueva@inmo.com')->first();

        $this->assertNotNull($user);
        $this->assertDatabaseHas('inmobiliarias', [
            'user_id' => $user->id,
            'nombre' => 'Test Inmobiliaria',
            'is_approved' => false,
        ]);
    }

    public function test_registro_crea_inmobiliaria_con_email_correcto(): void
    {
        $this->post('/register', [
            'name' => 'Mi Inmobiliaria',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('inmobiliarias', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_registro_loguea_al_usuario(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'nueva@inmo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
    }

    public function test_registro_redirecciona_al_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'nueva@inmo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_registro_con_email_duplicado_falla(): void
    {
        User::factory()->create(['email' => 'existente@inmo.com']);

        $response = $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'existente@inmo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registro_sin_contrasena_falla(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'nueva@inmo.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registro_con_contrasenias_que_no_coinciden_falla(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'nueva@inmo.com',
            'password' => 'password123',
            'password_confirmation' => 'diferente123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_inmobiliaria_recien_registrada_no_puede_acceder_al_panel(): void
    {
        $this->post('/register', [
            'name' => 'Test Inmobiliaria',
            'email' => 'nueva@inmo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'nueva@inmo.com')->first();

        // La inmobiliaria no aprobada no puede acceder al panel
        $response = $this->actingAs($user)->get('/panel');

        $response->assertRedirect('/');
    }
}

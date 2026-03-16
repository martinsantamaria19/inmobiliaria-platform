<?php

namespace Tests\Feature;

use App\Models\Inmobiliaria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlujoAprobacionTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superadmin = User::factory()->superadmin()->create();
    }

    public function test_superadmin_ve_inmobiliarias_pendientes(): void
    {
        $userPendiente = User::factory()->inmobiliaria()->create();
        $inmoPendiente = Inmobiliaria::factory()->pending()->create([
            'user_id' => $userPendiente->id,
            'nombre' => 'Inmobiliaria Pendiente Test',
        ]);

        $response = $this->actingAs($this->superadmin)->get('/admin/inmobiliarias');

        $response->assertOk();
        $response->assertSee('Inmobiliaria Pendiente Test');
    }

    public function test_superadmin_ve_todas_las_inmobiliarias(): void
    {
        $aprobadas = Inmobiliaria::factory()->approved()->count(3)->create();
        $pendientes = Inmobiliaria::factory()->pending()->count(2)->create();

        $response = $this->actingAs($this->superadmin)->get('/admin/inmobiliarias');

        $response->assertOk();
        // Deben aparecer tanto aprobadas como pendientes
        $this->assertEquals(5, Inmobiliaria::count());
    }

    public function test_superadmin_puede_aprobar_inmobiliaria(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        $inmo = Inmobiliaria::factory()->pending()->create(['user_id' => $user->id]);

        $this->assertFalse($inmo->fresh()->is_approved);

        $response = $this->actingAs($this->superadmin)
            ->post("/admin/inmobiliarias/{$inmo->id}/aprobar");

        $response->assertRedirect();
        $this->assertTrue($inmo->fresh()->is_approved);
    }

    public function test_superadmin_puede_rechazar_inmobiliaria(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        $inmo = Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $this->assertTrue($inmo->fresh()->is_approved);

        $response = $this->actingAs($this->superadmin)
            ->post("/admin/inmobiliarias/{$inmo->id}/rechazar");

        $response->assertRedirect();
        $this->assertFalse($inmo->fresh()->is_approved);
    }

    public function test_inmobiliaria_aprobada_puede_acceder_al_panel(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/panel');

        $response->assertOk();
    }

    public function test_inmobiliaria_pendiente_no_puede_acceder_al_panel(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->pending()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/panel');

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_inmobiliaria_sin_registro_no_puede_acceder_al_panel(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        // No se crea Inmobiliaria para este user

        $response = $this->actingAs($user)->get('/panel');

        $response->assertRedirect('/');
    }

    public function test_superadmin_filtra_inmobiliarias_por_estado_aprobada(): void
    {
        $userAprobado = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create([
            'user_id' => $userAprobado->id,
            'nombre' => 'Inmo Aprobada',
        ]);

        $userPendiente = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->pending()->create([
            'user_id' => $userPendiente->id,
            'nombre' => 'Inmo Pendiente',
        ]);

        $response = $this->actingAs($this->superadmin)
            ->get('/admin/inmobiliarias?estado=aprobada');

        $response->assertOk();
        $response->assertSee('Inmo Aprobada');
        $response->assertDontSee('Inmo Pendiente');
    }

    public function test_superadmin_filtra_inmobiliarias_por_estado_pendiente(): void
    {
        $userAprobado = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create([
            'user_id' => $userAprobado->id,
            'nombre' => 'Inmo Aprobada XYZ',
        ]);

        $userPendiente = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->pending()->create([
            'user_id' => $userPendiente->id,
            'nombre' => 'Inmo Pendiente ABC',
        ]);

        $response = $this->actingAs($this->superadmin)
            ->get('/admin/inmobiliarias?estado=pendiente');

        $response->assertOk();
        $response->assertDontSee('Inmo Aprobada XYZ');
        $response->assertSee('Inmo Pendiente ABC');
    }

    public function test_no_superadmin_no_puede_acceder_al_panel_admin(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/admin/inmobiliarias');

        $response->assertRedirect('/');
    }

    public function test_usuario_no_autenticado_no_puede_acceder_al_panel_admin(): void
    {
        $response = $this->get('/admin/inmobiliarias');

        $response->assertRedirect('/login');
    }
}

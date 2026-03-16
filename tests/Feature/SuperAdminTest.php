<?php

namespace Tests\Feature;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superadmin = User::factory()->superadmin()->create();
    }

    // --- Dashboard ---

    public function test_superadmin_puede_ver_admin_dashboard(): void
    {
        $response = $this->actingAs($this->superadmin)->get('/admin');

        $response->assertOk();
    }

    // --- Inmobiliarias pendientes ---

    public function test_superadmin_ve_inmobiliarias_pendientes(): void
    {
        $userPendiente = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->pending()->create([
            'user_id' => $userPendiente->id,
            'nombre' => 'Inmobiliaria Pendiente SA',
        ]);

        $userAprobada = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create([
            'user_id' => $userAprobada->id,
            'nombre' => 'Inmobiliaria Aprobada SRL',
        ]);

        $response = $this->actingAs($this->superadmin)
            ->get('/admin/inmobiliarias?estado=pendiente');

        $response->assertOk();
        $response->assertSee('Inmobiliaria Pendiente SA');
        $response->assertDontSee('Inmobiliaria Aprobada SRL');
    }

    // --- Aprobar inmobiliaria ---

    public function test_superadmin_puede_aprobar_inmobiliaria(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        $inmo = Inmobiliaria::factory()->pending()->create(['user_id' => $user->id]);

        $this->assertFalse($inmo->is_approved);

        $response = $this->actingAs($this->superadmin)
            ->post("/admin/inmobiliarias/{$inmo->id}/aprobar");

        $response->assertRedirect();
        $this->assertTrue($inmo->fresh()->is_approved);
    }

    // --- Rechazar/suspender inmobiliaria ---

    public function test_superadmin_puede_rechazar_inmobiliaria(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        $inmo = Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $this->assertTrue($inmo->is_approved);

        $response = $this->actingAs($this->superadmin)
            ->post("/admin/inmobiliarias/{$inmo->id}/rechazar");

        $response->assertRedirect();
        $this->assertFalse($inmo->fresh()->is_approved);
    }

    // --- Propiedades ---

    public function test_superadmin_ve_todas_las_propiedades(): void
    {
        Property::factory()->count(5)->create();

        $response = $this->actingAs($this->superadmin)->get('/admin/propiedades');

        $response->assertOk();
        $this->assertEquals(5, Property::count());
    }

    // --- Consultas ---

    public function test_superadmin_ve_consultas(): void
    {
        Inquiry::factory()->count(3)->create();

        $response = $this->actingAs($this->superadmin)->get('/admin/consultas');

        $response->assertOk();
    }

    // --- Restricciones de acceso ---

    public function test_usuario_no_autenticado_no_puede_acceder_a_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_inmobiliaria_no_puede_acceder_a_admin(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/admin');

        // Debe redirigir (no puede acceder), no 200
        $response->assertRedirect();
        $this->assertNotEquals(200, $response->getStatusCode());
    }
}

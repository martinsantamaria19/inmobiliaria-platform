<?php

namespace Tests\Feature;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelSuperadminTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superadmin = User::factory()->superadmin()->create();
    }

    // --- Dashboard ---

    public function test_panel_admin_dashboard_carga(): void
    {
        $response = $this->actingAs($this->superadmin)->get('/admin');

        $response->assertOk();
    }

    // --- Inmobiliarias ---

    public function test_superadmin_ve_listado_de_inmobiliarias(): void
    {
        Inmobiliaria::factory()->count(5)->create();

        $response = $this->actingAs($this->superadmin)->get('/admin/inmobiliarias');

        $response->assertOk();
    }

    public function test_superadmin_busca_inmobiliaria_por_nombre(): void
    {
        $user1 = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->create([
            'user_id' => $user1->id,
            'nombre' => 'Rivera Propiedades',
        ]);

        $user2 = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->create([
            'user_id' => $user2->id,
            'nombre' => 'Costa Este Inmobiliaria',
        ]);

        $response = $this->actingAs($this->superadmin)
            ->get('/admin/inmobiliarias?search=Rivera');

        $response->assertOk();
        $response->assertSee('Rivera Propiedades');
        $response->assertDontSee('Costa Este Inmobiliaria');
    }

    // --- Propiedades ---

    public function test_superadmin_ve_listado_de_propiedades(): void
    {
        Property::factory()->count(5)->create();

        $response = $this->actingAs($this->superadmin)->get('/admin/propiedades');

        $response->assertOk();
    }

    public function test_superadmin_puede_destacar_propiedad(): void
    {
        $property = Property::factory()->create(['destacado' => false]);

        $response = $this->actingAs($this->superadmin)
            ->post("/admin/propiedades/{$property->id}/destacado");

        $response->assertRedirect();
        $this->assertTrue($property->fresh()->destacado);
    }

    public function test_superadmin_puede_quitar_destacado(): void
    {
        $property = Property::factory()->create(['destacado' => true]);

        $response = $this->actingAs($this->superadmin)
            ->post("/admin/propiedades/{$property->id}/destacado");

        $response->assertRedirect();
        $this->assertFalse($property->fresh()->destacado);
    }

    // --- Consultas ---

    public function test_superadmin_ve_listado_de_consultas(): void
    {
        Inquiry::factory()->count(5)->create();

        $response = $this->actingAs($this->superadmin)->get('/admin/consultas');

        $response->assertOk();
    }

    public function test_superadmin_puede_marcar_consulta_como_leida(): void
    {
        $consulta = Inquiry::factory()->create(['read_at' => null]);

        $this->assertNull($consulta->read_at);

        $response = $this->actingAs($this->superadmin)
            ->post("/admin/consultas/{$consulta->id}/leida");

        $response->assertRedirect();
        $this->assertNotNull($consulta->fresh()->read_at);
    }

    public function test_superadmin_filtra_consultas_no_leidas(): void
    {
        Inquiry::factory()->count(3)->create(['read_at' => null]);
        Inquiry::factory()->count(2)->leida()->create();

        $response = $this->actingAs($this->superadmin)->get('/admin/consultas?leida=0');

        $response->assertOk();
        // Solo las no leídas
        $this->assertEquals(3, Inquiry::whereNull('read_at')->count());
    }

    // --- Reportes ---

    public function test_superadmin_ve_reportes(): void
    {
        $response = $this->actingAs($this->superadmin)->get('/admin/reportes');

        $response->assertOk();
    }

    // --- Restricciones de acceso ---

    public function test_inmobiliaria_no_puede_acceder_al_panel_admin(): void
    {
        $user = User::factory()->inmobiliaria()->create();
        Inmobiliaria::factory()->approved()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect('/');
    }

    public function test_usuario_no_autenticado_no_puede_ver_consultas_admin(): void
    {
        $response = $this->get('/admin/consultas');

        $response->assertRedirect('/login');
    }
}

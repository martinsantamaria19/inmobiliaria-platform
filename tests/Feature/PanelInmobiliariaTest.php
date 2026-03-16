<?php

namespace Tests\Feature;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelInmobiliariaTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Inmobiliaria $inmo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->inmobiliaria()->create();
        $this->inmo = Inmobiliaria::factory()->approved()->create([
            'user_id' => $this->user->id,
        ]);
    }

    // --- Dashboard ---

    public function test_panel_dashboard_carga_correctamente(): void
    {
        $response = $this->actingAs($this->user)->get('/panel');

        $response->assertOk();
    }

    // --- Listado de propiedades ---

    public function test_panel_lista_propiedades_de_la_inmobiliaria(): void
    {
        Property::factory()->count(3)->create(['inmobiliaria_id' => $this->inmo->id]);

        $response = $this->actingAs($this->user)->get('/panel/propiedades');

        $response->assertOk();
        $this->assertEquals(3, $this->inmo->properties()->count());
    }

    // --- Crear propiedad ---

    public function test_formulario_crear_propiedad_carga_correctamente(): void
    {
        $response = $this->actingAs($this->user)->get('/panel/propiedades/create');

        $response->assertOk();
    }

    public function test_crear_propiedad_exitosamente(): void
    {
        $response = $this->actingAs($this->user)->post('/panel/propiedades', [
            'titulo' => 'Casa en Pocitos',
            'descripcion' => 'Hermosa casa con jardín',
            'tipo' => 'casa',
            'operacion' => 'venta',
            'precio_venta' => 250000,
            'precio_alquiler' => null,
            'moneda' => 'USD',
            'estado' => 'disponible',
            'dormitorios' => 3,
            'banos' => 2,
            'superficie_total' => 180,
            'superficie_construida' => 150,
            'direccion' => 'Calle Test 123',
            'barrio' => 'Pocitos',
            'ciudad' => 'Montevideo',
            'garage' => false,
            'piscina' => false,
        ]);

        $response->assertRedirect(route('panel.propiedades'));
        $this->assertDatabaseHas('properties', [
            'inmobiliaria_id' => $this->inmo->id,
            'titulo' => 'Casa en Pocitos',
            'tipo' => 'casa',
        ]);
    }

    public function test_crear_propiedad_falla_sin_titulo(): void
    {
        $response = $this->actingAs($this->user)->post('/panel/propiedades', [
            'titulo' => '',
            'descripcion' => 'Descripcion',
            'tipo' => 'casa',
            'operacion' => 'venta',
            'moneda' => 'USD',
            'estado' => 'disponible',
            'dormitorios' => 2,
            'banos' => 1,
            'ciudad' => 'Montevideo',
        ]);

        $response->assertSessionHasErrors('titulo');
    }

    // --- Editar propiedad ---

    public function test_formulario_editar_propiedad_carga_correctamente(): void
    {
        $property = Property::factory()->create(['inmobiliaria_id' => $this->inmo->id]);

        $response = $this->actingAs($this->user)->get("/panel/propiedades/{$property->id}/edit");

        $response->assertOk();
    }

    public function test_actualizar_propiedad_exitosamente(): void
    {
        $property = Property::factory()->create([
            'inmobiliaria_id' => $this->inmo->id,
            'titulo' => 'Título original',
        ]);

        $response = $this->actingAs($this->user)->put("/panel/propiedades/{$property->id}", [
            'titulo' => 'Título actualizado',
            'descripcion' => 'Nueva descripción',
            'tipo' => 'apartamento',
            'operacion' => 'alquiler',
            'precio_venta' => null,
            'precio_alquiler' => 800,
            'moneda' => 'USD',
            'estado' => 'disponible',
            'dormitorios' => 2,
            'banos' => 1,
            'ciudad' => 'Montevideo',
            'garage' => false,
            'piscina' => false,
        ]);

        $response->assertRedirect(route('panel.propiedades'));
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'titulo' => 'Título actualizado',
        ]);
    }

    public function test_inmobiliaria_no_puede_editar_propiedad_de_otra(): void
    {
        $otraInmo = Inmobiliaria::factory()->approved()->create();
        $propiedadAjena = Property::factory()->create(['inmobiliaria_id' => $otraInmo->id]);

        $response = $this->actingAs($this->user)->get("/panel/propiedades/{$propiedadAjena->id}/edit");

        $response->assertNotFound();
    }

    // --- Eliminar propiedad ---

    public function test_eliminar_propiedad_exitosamente(): void
    {
        $property = Property::factory()->create(['inmobiliaria_id' => $this->inmo->id]);

        $response = $this->actingAs($this->user)->delete("/panel/propiedades/{$property->id}");

        $response->assertRedirect(route('panel.propiedades'));
        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
    }

    public function test_inmobiliaria_no_puede_eliminar_propiedad_de_otra(): void
    {
        $otraInmo = Inmobiliaria::factory()->approved()->create();
        $propiedadAjena = Property::factory()->create(['inmobiliaria_id' => $otraInmo->id]);

        $response = $this->actingAs($this->user)->delete("/panel/propiedades/{$propiedadAjena->id}");

        $response->assertNotFound();
        $this->assertDatabaseHas('properties', ['id' => $propiedadAjena->id]);
    }

    // --- Consultas ---

    public function test_panel_lista_consultas(): void
    {
        $property = Property::factory()->create(['inmobiliaria_id' => $this->inmo->id]);
        Inquiry::factory()->count(3)->create(['property_id' => $property->id]);

        $response = $this->actingAs($this->user)->get('/panel/consultas');

        $response->assertOk();
    }

    public function test_panel_puede_marcar_consulta_como_leida(): void
    {
        $property = Property::factory()->create(['inmobiliaria_id' => $this->inmo->id]);
        $consulta = Inquiry::factory()->create([
            'property_id' => $property->id,
            'read_at' => null,
        ]);

        $this->assertNull($consulta->read_at);

        $response = $this->actingAs($this->user)
            ->post("/panel/consultas/{$consulta->id}/leida");

        $response->assertRedirect();
        $this->assertNotNull($consulta->fresh()->read_at);
    }

    // --- Perfil ---

    public function test_perfil_carga_correctamente(): void
    {
        $response = $this->actingAs($this->user)->get('/panel/perfil');

        $response->assertOk();
    }

    public function test_actualizar_perfil_exitosamente(): void
    {
        $response = $this->actingAs($this->user)->put('/panel/perfil', [
            'nombre' => 'Nuevo Nombre Inmobiliaria',
            'descripcion' => 'Nueva descripción',
            'telefono' => '2900-1234',
            'email' => 'nuevo@email.com',
            'direccion' => 'Nueva dirección 123',
            'ciudad' => 'Montevideo',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('inmobiliarias', [
            'id' => $this->inmo->id,
            'nombre' => 'Nuevo Nombre Inmobiliaria',
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalPublicoTest extends TestCase
{
    use RefreshDatabase;

    // --- Home ---

    public function test_home_carga_correctamente(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_home_muestra_propiedades_destacadas(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $destacada = Property::factory()->create([
            'inmobiliaria_id' => $inmo->id,
            'destacado' => true,
            'estado' => 'disponible',
            'titulo' => 'Propiedad Destacada Test',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Propiedad Destacada Test');
    }

    // --- Listado de propiedades ---

    public function test_listado_de_propiedades_carga(): void
    {
        $response = $this->get('/propiedades');

        $response->assertOk();
    }

    public function test_listado_muestra_propiedades_disponibles(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        Property::factory()->count(3)->create([
            'inmobiliaria_id' => $inmo->id,
            'estado' => 'disponible',
            'titulo' => 'Propiedad Disponible',
        ]);

        $response = $this->get('/propiedades');

        $response->assertOk();
        $this->assertEquals(3, Property::where('estado', 'disponible')->count());
    }

    public function test_listado_filtra_por_tipo(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        Property::factory()->create([
            'inmobiliaria_id' => $inmo->id,
            'tipo' => 'casa',
            'estado' => 'disponible',
            'titulo' => 'Casa test',
        ]);
        Property::factory()->create([
            'inmobiliaria_id' => $inmo->id,
            'tipo' => 'apartamento',
            'estado' => 'disponible',
            'titulo' => 'Apartamento test',
        ]);

        $response = $this->get('/propiedades?tipo=casa');

        $response->assertOk();
        $response->assertSee('Casa test');
        $response->assertDontSee('Apartamento test');
    }

    public function test_listado_filtra_por_operacion(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        Property::factory()->create([
            'inmobiliaria_id' => $inmo->id,
            'operacion' => 'venta',
            'estado' => 'disponible',
            'titulo' => 'Casa en venta test',
        ]);
        Property::factory()->create([
            'inmobiliaria_id' => $inmo->id,
            'operacion' => 'alquiler',
            'estado' => 'disponible',
            'titulo' => 'Apto en alquiler test',
        ]);

        $response = $this->get('/propiedades?operacion=venta');

        $response->assertOk();
        $response->assertSee('Casa en venta test');
        $response->assertDontSee('Apto en alquiler test');
    }

    // --- Detalle de propiedad ---

    public function test_detalle_de_propiedad_carga(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create([
            'inmobiliaria_id' => $inmo->id,
            'titulo' => 'Casa en Pocitos',
        ]);

        $response = $this->get("/propiedades/{$property->id}");

        $response->assertOk();
        $response->assertSee('Casa en Pocitos');
    }

    public function test_detalle_incrementa_contador_de_vistas(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create([
            'inmobiliaria_id' => $inmo->id,
            'views_count' => 10,
        ]);

        $this->get("/propiedades/{$property->id}");

        $this->assertEquals(11, $property->fresh()->views_count);
    }

    public function test_detalle_de_propiedad_inexistente_devuelve_404(): void
    {
        $response = $this->get('/propiedades/99999');

        $response->assertNotFound();
    }

    // --- Formulario de consulta ---

    public function test_formulario_de_consulta_envia_exitosamente(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create(['inmobiliaria_id' => $inmo->id]);

        $response = $this->post("/propiedades/{$property->id}/consulta", [
            'nombre' => 'Juan García',
            'email' => 'juan@gmail.com',
            'telefono' => '099123456',
            'mensaje' => 'Me interesa la propiedad, ¿podemos coordinar una visita?',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', [
            'property_id' => $property->id,
            'nombre' => 'Juan García',
            'email' => 'juan@gmail.com',
        ]);
    }

    public function test_consulta_sin_nombre_falla(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create(['inmobiliaria_id' => $inmo->id]);

        $response = $this->post("/propiedades/{$property->id}/consulta", [
            'nombre' => '',
            'email' => 'juan@gmail.com',
            'mensaje' => 'Me interesa la propiedad',
        ]);

        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_consulta_sin_email_falla(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create(['inmobiliaria_id' => $inmo->id]);

        $response = $this->post("/propiedades/{$property->id}/consulta", [
            'nombre' => 'Juan García',
            'email' => '',
            'mensaje' => 'Me interesa la propiedad',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_consulta_con_email_invalido_falla(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create(['inmobiliaria_id' => $inmo->id]);

        $response = $this->post("/propiedades/{$property->id}/consulta", [
            'nombre' => 'Juan García',
            'email' => 'noesunemail',
            'mensaje' => 'Me interesa la propiedad',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_consulta_sin_mensaje_falla(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create(['inmobiliaria_id' => $inmo->id]);

        $response = $this->post("/propiedades/{$property->id}/consulta", [
            'nombre' => 'Juan García',
            'email' => 'juan@gmail.com',
            'mensaje' => '',
        ]);

        $response->assertSessionHasErrors('mensaje');
    }

    public function test_consulta_sin_telefono_es_valida(): void
    {
        $inmo = Inmobiliaria::factory()->approved()->create();
        $property = Property::factory()->create(['inmobiliaria_id' => $inmo->id]);

        $response = $this->post("/propiedades/{$property->id}/consulta", [
            'nombre' => 'Juan García',
            'email' => 'juan@gmail.com',
            'mensaje' => 'Me interesa la propiedad',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('inquiries', [
            'property_id' => $property->id,
            'telefono' => null,
        ]);
    }
}

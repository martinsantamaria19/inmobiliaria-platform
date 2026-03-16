<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Nota: Las rutas /profile fueron removidas de esta plataforma.
 * Estos tests de Breeze se marcan como skipped ya que no aplican.
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $this->markTestSkipped('Ruta /profile no implementada en esta plataforma.');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $this->markTestSkipped('Ruta /profile no implementada en esta plataforma.');
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $this->markTestSkipped('Ruta /profile no implementada en esta plataforma.');
    }

    public function test_user_can_delete_their_account(): void
    {
        $this->markTestSkipped('Ruta /profile no implementada en esta plataforma.');
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $this->markTestSkipped('Ruta /profile no implementada en esta plataforma.');
    }
}

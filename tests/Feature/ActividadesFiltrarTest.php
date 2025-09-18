<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActividadesFiltrarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function filtrar_sin_parcial_id_devuelve_400()
    {
        $response = $this->get('/profesores/actividades/filtrar');
        $response->assertStatus(400);
        $response->assertJsonStructure(['success', 'message']);
    }
}

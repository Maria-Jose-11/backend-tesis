<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    

    public function it_visit_page_of_login()
    {
        $request = $this->get('/api/v1/emprendimiento');
        $request ->assertStatus(200);

    }

    public function ver_videoconferencias()
    {
        $request = $this->get('/api/v1/videoconferencia');
        $request ->assertStatus(200);

    }

    public function ver_usuario_admin()
    {
        $request = $this->get('/api/v1/admin8');
        $request ->assertStatus(200);

    }
}

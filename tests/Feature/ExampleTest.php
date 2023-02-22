<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function ver_videoconferencias()
    {
        $request = $this->get('/api/v1/videoconferencia');
        $request ->assertStatus(200);

    }
}

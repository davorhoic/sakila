<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeText('wonderful documentation');

          $response = $this->get('/x');

        $response->assertStatus(404);    
        //TODO ne radi, nije radilo jer je bio upppercase slucaj
        $response->assertSeeText('Not Found');
    }
}

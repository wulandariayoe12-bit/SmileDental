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

        $response->assertRedirect('/login');
    }

    public function test_crud_pages_require_login_session(): void
    {
        $this->get('/patients')->assertRedirect('/login');
        $this->get('/appointments')->assertRedirect('/login');
        $this->get('/payments')->assertRedirect('/login');
    }

    public function test_destructive_routes_are_not_available_as_get_requests(): void
    {
        $this->get('/patients/delete/1')->assertNotFound();
        $this->get('/logout')->assertMethodNotAllowed();
    }
}

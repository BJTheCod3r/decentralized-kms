<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testUserCanLogin()
    {
        // Define your login data
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        User::factory()->create($loginData);


        // Simulate a POST request to the login route
        $response = $this->post(route('login'), $loginData);

        // Assert that the response is successful (status code 200)
        $response->assertStatus(200);

        // You can further assert the response content as needed
        $response->assertJson(['message' => User::SIGNIN_SUCCESSFUL]);
    }
}


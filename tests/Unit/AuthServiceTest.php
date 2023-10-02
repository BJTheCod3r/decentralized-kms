<?php
namespace Tests\Unit;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testSignin()
    {
        // Create a mock UserRepository
        $userRepository = $this->createMock(UserRepository::class);
        $this->app->instance(UserRepository::class, $userRepository);

        // Create an instance of AuthService
        $authService = new AuthService($userRepository);

        // Create a mock LoginRequest
        $loginRequest = new LoginRequest([
            'email' => 'test@gmail.com',
            'password' => 'password'
        ]);

        // Mock UserRepository to return a user
        $user = User::factory()->create([
            'email' => 'test@gmail.com'
        ]);

        $userRepository->method('findOneBy')->willReturn($user);

        // Mock Hash::check to return true
        Hash::shouldReceive('check')->andReturn(true);

        // Call the signin method
        $response = $authService->signin($loginRequest);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response data includes a 'user' and 'token'
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $responseData['data']);
        $this->assertArrayHasKey('token', $responseData['data']);
    }

    public function testCreateToken()
    {
        // Create a user for testing
        $user = User::factory()->create();

        // Create an instance of AuthService
        $authService = new AuthService($this->app->make(UserRepository::class));

        // Call the createToken method
        $token = $authService->createToken($user, 'device_name');

        // Assert that the token is a non-empty string
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

}
